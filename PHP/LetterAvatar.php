<?php
    /**
   * LetterAvatar
   * 
   * 
   * @author Mario Greiner <hello@greiner-mario.at>
   * @see https://github.com/Mario-Greiner/Snippets/blob/master/PHP/Examples/LetterAvatar.php
   */
    class LetterAvatar {
        /**
         * @var string
         */
        private $name;

        /**
         * Image width
         * Default: 200
         * @var int
         */
        private $width;


        /**
         * Image height
         * Default: 200
         * @var int
         */
        private $height;

        /**
         * @var int
         */
        private $fontSize;

        /**
         * Backgroundcolor 
         * Default: 2e54d3
         * @var array
         */
        private $backgroundColor;

        /**
         * TextColor 
         * Default: fff
         * @var array
         */
        private $textColor;

        public function __construct(string $name, int $width = 200, int $height = 200, int $fontSize = 50, string $backgroundColor = '2e54d3', string $textColor = 'fff'){
            $this->name = $this->getInitials($name);
            $this->width = $width;
            $this->height = $height;
            $this->fontSize = $fontSize;
            $this->backgroundColor = $this->convertHexToRGB($backgroundColor);
            $this->textColor = $this->convertHexToRGB($textColor);
        }

        public function display(){
            //Create image
            $img = imagecreate($this->width, $this->height);

            //Backgroundcolor
            imagecolorallocate($img, $this->backgroundColor[0], $this->$this->backgroundColor[1], $this->$this->backgroundColor[2]);

            //textcolor
            $fontColor = imagecolorallocate($img, $this->textColor[0], $this->textColor[1], $this->textColor[2]);

            //Path to font
            $fontPath = $_SERVER['DOCUMENT_ROOT'] . '/fonts/Nunito-Regular.ttf';

            //Create text
            $text = imagettfbbox($this->fontSize, 0, $fontPath, $this->name);

            //Center text
            $y = abs(ceil(($this->height - $text[5]) / 2));
            $x = abs(ceil(($this->width - $text[2]) / 2));

            //Add text to image
            imagettftext($img, $this->fontSize, 0, $x, $y, $fontColor, $fontPath, $this->name);

            //Send image to browser
            imagejpeg($img);

            //Delete image
            imagedestroy($img);
        }

        /**
        * Get the first Letter of every name 
        * Example: "Mario Greiner" returns "MG"
        * 
        * @param string $name
        * @return string The first letter of every Name/Word (case sensetive)
        */
        public function getInitials(string $name): string{
            $initials = '';
            $nameParts = $this->splitName($name);

            //Loop over name in case of middle name
            for($i = 0; $i < sizeof($nameParts); $i++){
                $initials .= $this->getFirstLetter($nameParts[$i]);
            }

            return $initials;
        }

        /**
         * Returns the first letter of the given word
         *
         * @param string $word
         * @return string The first letter of $word
         */
        private function getFirstLetter(string $word): string{
            return mb_strtoupper(trim(mb_substr($word, 0, 1, 'UTF-8')));
        }
    
        /**
        * Splits the given name into an array.
        * The function will check if a part is , or blank
        *
        * @param string $name Name to be split
        * @return array Name splitted up into an array
        */
        private function splitName(string $name): array{
            $words = \explode(' ', $name);
            $words = array_filter($words, function($word) {
                return $word!=='' && $word !== ',';
            });
            return array_values($words);
        }

        private function convertHexToRGB(string $hexCode){
            //Remove # when needed
            if(strpos($hexCode, '#') !== false){
                $hexCode = trim($hexCode,'#');
            }

            $this->backgroundColor = sscanf($hexCode, '%2x%2x%2x');
        }

    }
