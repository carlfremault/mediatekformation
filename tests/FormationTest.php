<?php

use App\Entity\Formation;
use App\Entity\Niveau;
use PHPUnit\Framework\TestCase;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of FormationTest
 *
 * @author carlf
 */
class FormationTest extends TestCase {
    
    public function testGetPublishedAtString() {
        $formation = new Formation();
        $formation->setPublishedAt(new DateTime("2022-02-22"));
        $this->assertEquals("22/02/2022", $formation->getPublishedAtString());
    }
    
    public function testGetNiveauString(){
        $formation = new Formation();
        $niveau = new Niveau();
        $niveau->setLibelle("testNiveau");
        $formation->setNiveau($niveau);
        $this->assertEquals($niveau->getLibelle(), $formation->getNiveauString());
    }
}
