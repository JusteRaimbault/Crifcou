<?php

    class CurrentPage{

        public $name;
        public $title;
        public $titlemenu;
        public $authorized;

        public function __construct($n,$t,$tm,$a){
            $this->name=$n;
            $this->title=$t;
            $this->titlemenu=$tm;
            $this->authorized=$a;
        }

        public static function acceuil(){
            return new CurrentPage("acceuil", "Accueil", "accueil in menu", "ALL");
        }

    }


    class Utilisateur{

        public $login;
        public $nom;
        public $password;//recupéré codé en SHA1()
        public $mail;
        public $valid;
        public $isroot;

    }

    class Carte{

        public $filename;
        public $lat;
        public $lon;
        public $place;
        public $owner;
        public $processed;

    }



?>
