<?php namespace Jewel\Handlers;

class HandlerUtilities
{
	/**
	 * Adds relevant Web-One styles to the data (table markup, in this case) and
	 * returns the modified markup.
	 *
	 * @param string $data The data to modify
	 * @return string
	 */
	public static function addWebOneStyle($data) {
		// Adds border="0" to table tage
		$data = str_replace("<table>", "<table border=\"0\">", $data);

		// Tags every even tr with an even class and every odd tr with an odd class
		$i=0;
		$data = preg_replace_callback('/\<tr\>/',function($matches) use(&$i){		
			$val = '<tr class="'.($i%2==0?'even':'odd').'">';
			$i++;
			return $val;
		},
		$data);

		return $data;
	}

	/**
	 * Takes a formatted attribute string and converts it to an array. In
	 * the attribute string, key/value pairs can be separated by pipe characters.
	 * The array that comes back is associative.
	 *
	 * @param string $attributes The pipe-separated key:value attribute string
	 * @param string $sep Separator between element pairs (defaults to |)
	 * @param string $elementSep Separator between each key and value (defaults to :)
	 * @return array
	 *
	 * @example
	 * $attributes = "key1:value1|key2:value2";
	 * $arr = HandlerUtilities::pipedAttributesToArray($attributes);
	 * var_dump($arr);
	 *
	 * array(2) { ["key1"]=> string(6) "value1", ["key2"]=> string(6) "value2" }
	 */
	public static function attributesToArray($attributes, $sep="|", $elementSep=":") {
		$exploded = explode($sep, $attributes);
		$keys = [];
		$values = [];

		// iterate over each set of exploded pairs
		foreach($exploded as $pair) {
			$elements = explode($elementSep, $pair);
			$keys[] = $elements[0];
			$values[] = (!empty($elements[1]) ? $elements[1] : "");
		}

		return array_combine($keys, $values);
	}

	/**
	 * Removes some control characters from the given string.
	 *
	 * @param string $string The string from which to remove characters
	 * @return string
	 */
	public static function removeControlCharacters($string) {
		return preg_replace('/(\\n)|(\\t)/', '', $string);
	}
}