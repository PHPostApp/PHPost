<?php 

/**
 * Autor: Miguel92
 * Ejemplo: {googlefonts fonts=['string|array']} 
 * Enlace: https://fonts.google.com/
 * Fecha: Feb 28, 2024 
 * Nombre: googlefonts
 * Proposito: Añadir fuentes desde la página https://fonts.google.com/
 * Tipo: function 
 * Version: 1.0 
*/

class GoogleFonts {

	private $fonts;

	private $selected;

	/**
	 * @access public
    * Constructor de la clase GoogleFonts.
    * 
    * @param mixed $font La fuente seleccionada. Puede ser un string o un array de strings.
   */
	public function __construct($font = '') {
		$this->selected = $font;
		$this->fonts = [
			['name' => 'Bebas Neue', 'weight' => ''],
			['name' => 'Comfortaa', 'weight' => ':wght@300..700'],
			['name' => 'Lato', 'weight' => ':wght@100;300;400;700;900'],
			['name' => 'Montserrat', 'weight' => ':ital,wght@0,100..900;1,100..900'],
			['name' => 'Open Sans', 'weight' => ':ital,wght@0,300..800;1,300..800'],
			['name' => 'Oswald', 'weight' => ':wght@200..700'],
			['name' => 'Poppins', 'weight' => ':wght@100;200;300;400;500;600;700;800;900'],
			['name' => 'Proza Libre', 'weight' => ':wght@400;500;600;700;800'],
			['name' => 'Raleway', 'weight' => ':ital,wght@0,100..900;1,100..900'],
			['name' => 'Roboto', 'weight' => ':wght@100;300;400;500;700;900'],
			['name' => 'Work Sans', 'weight' => ':ital,wght@0,100..900;1,100..900']
		];
	}

	/**
	 * @access private
    * Genera las etiquetas de preconexión para las fuentes de Google.
    * 
    * @return string Las etiquetas de preconexión.
   */
	private function preconnect():string {
		return <<<PRECONNECT
		<!-- Plugin GoogleFonts v1 creado por Miguel92 -->
		<link rel="preconnect" href="https://fonts.googleapis.com">
		<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>\n
		PRECONNECT;
	}

	/**
	 * @access private
    * Busca una fuente específica y devuelve la cadena de consulta correspondiente.
    * 
    * @param string $fonts El nombre de la fuente.
    * @return string La cadena de consulta para la fuente especificada.
   */
	private function SearchFont(string $fonts = ''):string {
		foreach($this->fonts as $fontKey => $fontweight) {
			$fontSearch = $fontweight['name'];
			# Si la fuente coindide
			if(strtolower($fontSearch) == strtolower($fonts)) {
				# Usamos otra variable para retornar todas las fuentes
				$response = 'family=' . preg_replace('/\s+/', '+', $fontSearch) . $fontweight['weight'];
			}
		}
		return $response;
	}

	/**
	 * @access private
    * Busca una serie de fuentes y devuelve un array con las cadenas de consulta correspondientes.
    * 
    * @param array $fonts El array de nombres de fuentes.
    * @return array Un array con las cadenas de consulta para las fuentes especificadas.
   */
	private function SearchFontArray(array $fonts = []):array {
		foreach($fonts as $k => $font) $response[$k] = self::SearchFont($font);
		return $response;
	}

	/**
	 * @access public
    * Genera las etiquetas de enlace para las fuentes de Google.
    * 
    * @return string Las etiquetas de enlace para las fuentes seleccionadas.
   */
	public function addingFonts():string {
		$fonts = empty($this->selected) ? 'Roboto' : $this->selected;
		$font = is_array($fonts) ? implode("&", self::SearchFontArray($fonts)) : self::SearchFont($fonts);
		$googlefont[] = self::preconnect();
		$googlefont[] = "<link rel=\"stylesheet\" href=\"https://fonts.googleapis.com/css2?$font&display=swap\">\n";
		return implode("", $googlefont);
	}

}

function smarty_function_googlefonts($params, &$smarty) {
	$GoogleFonts = new GoogleFonts($params['fonts']);
	return trim($GoogleFonts->addingFonts());
}