<?php 

namespace App\Services\Fixture;

class MatchFixture { 

    private $aux = array(); 
    private $pair = array(); 
    private $odd = array(); 
    private $countGames = 0; 
    private $countTeams = 0; 
    
    public function __construct(array $teams) { 
        if(is_array($teams)){ 
            shuffle($teams); 
            $this->countTeams = count($teams); 
            if($this->countTeams % 2 == 1){ 
                $this->countTeams++; 
                $teams[] = "free this round"; 
            } 
            $this->countGames = floor($this->countTeams/2); 
            for($i = 0; $i < $this->countTeams; $i++){ 
                $this->aux[] = $teams[$i]; 
            } 
        }else{ 
            return false; 
        } 
    } 
    
    private function init(){ 
        for($x = 0; $x < $this->countGames; $x++){ 
            $this->pair[$x][0] = $this->aux[$x]; 
            $this->pair[$x][1] = $this->aux[($this->countTeams - 1) - $x]; 
        } 
        return $this->pair; 
    } 

    public function getSchedule(){ 
        $rol = array(); 
        $rol[] = $this->init(); 
        for($y = 1; $y < $this->countTeams-1; $y++){ 
            if($y % 2 == 0){ 
                $rol[] = $this->getPairRound(); 
            }else{ 
                $rol[] = $this->getOddRound(); 
            } 
        } 
        return $rol; 
    } 
    
    private function getPairRound(){ 
        for($z = 0; $z < $this->countGames; $z++){ 
            if($z == 0){ 
                $this->pair[$z][0] = $this->odd[$z][0]; 
                $this->pair[$z][1] = $this->odd[$z + 1][0]; 
            }elseif($z == $this->countGames-1){ 
                $this->pair[$z][0] = $this->odd[0][1]; 
                $this->pair[$z][1] = $this->odd[$z][1]; 
            }else{ 
                $this->pair[$z][0] = $this->odd[$z][1]; 
                $this->pair[$z][1] = $this->odd[$z + 1][0]; 
            } 
        } 
        return $this->pair; 
    } 
    
    private function getOddRound(){ 
        for($j = 0; $j < $this->countGames; $j++){ 
            if($j == 0){ 
                $this->odd[$j][0] = $this->pair[$j][1]; 
                $this->odd[$j][1] = $this->pair[$this->countGames - 1][0]; //Pivot 
            }else{ 
                $this->odd[$j][0] = $this->pair[$j][1]; 
                $this->odd[$j][1] = $this->pair[$j - 1][0]; 
            } 
        } 
        return $this->odd; 
    } 
} 