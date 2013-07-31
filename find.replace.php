<?php
	
class Ip_Find_Replace
{

	private $find;
	
	private $replace;

	/*
	* Initialize the find/replace values
	* @param string $find - word(s) to find
	* @param string $replace - word(s) that will replace $find
	*/	
	function __construct( $find, $replace ){		
		$this->find = $find;		
		$this->replace = $replace;		
		return $this;		
	}

	/*
	* Public function
	* @param array $data - array of db locations
	* @return integer - results count
	*/
	public function start( $data ){		
		$count = 0;		
		foreach( $data as $arr ){		
			$count += $this->query( $arr );		
		}		
		return $count;		
	}

	/*
	* Find and replace data in db tables
	* @param array $data - db locations
	* @return integer - results count
	*/	
	private function query( $arr ){		
		global $wpdb;		
		list($table, $field, $id) = $arr;		
		$query = $wpdb->prepare(
			"SELECT * FROM {$wpdb->prefix}{$table} WHERE {$field} LIKE '%s'",
			array('%'.$_POST['find'].'%')
		);		
		$data = $wpdb->get_results( $query, ARRAY_A );				
		foreach($data as $key => $val){		
			if(@unserialize($val[$field])){				
				$val[$field] = serialize( $this->find( unserialize( $val[$field] ) ) );			
			} else {				
				$val[$field] = $this->find( $val[$field] );				
			}			
			$wpdb->query(
				$wpdb->prepare(
					"UPDATE {$wpdb->prefix}{$table} SET {$field} = '%s' WHERE {$id} = '%s'",
					array(
						$val[$field],
						$val[$id]
					)
				)
			);					
		}		
		return count( $data );		
	}
	
	/*
	* String Replace
	* @param string $data 
	* @return string - replaced data
	*/	
	private function replace( $subject ){	
		return str_replace( 
			array(
				urlencode($this->find), 
				$this->find
			),
			array(
				urlencode($this->replace), 
				$this->replace
			), 
			$subject 
		);	
	}
	
	/*
	* String Replace
	* @param mixed $data 
	* @return bool - is_object
	*/	
	private function isobject( $type ){		
		return is_object( $type ) || gettype( $type ) == "object";		
	}
	
	/*
	* Traverse array
	* @param array $data 
	* @return array - replaced data
	*/	
	private function findeach( $data ){		
		$results = array();		
		foreach( $data as $key => $val ){			
			$results[$key] = is_array( $val ) ?
				$this->find( $val ) : (
					$this->isobject( $val ) ?
					$val : 
					$this->replace( $val )
				);			
		}		
		return $results;		
	}
		
	/*
	* Recursive replace strings in arrays and preserve keys
	* @param mixed $data
	* @return mixed - replaced data
	*/	
	private function find( $data ){		
		return $this->isobject( $data ) ? 
			$data : (
				is_array( $data ) ?
				$this->findeach( $data ) :
				$this->replace( $data )				
			);		
	}
	
}