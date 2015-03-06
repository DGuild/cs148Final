<?php
include('data/connect.php');

try { 
            $db->beginTransaction(); 
            
            $sql = 'SELECT * FROM tblUser;'; 
            $stmt = $db->prepare($sql); 
            if ($debug) print "<p>sql: ". $sql; 
        
            $stmt->execute(); 

            // all sql statements are done so lets commit to our changes 
            $dataEntered = $db->commit(); 
            
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            if ($debug) print "<p>transaction complete "; 
        } catch (PDOExecption $e) { 
            $db->rollback(); 
            if ($debug) print "Error!: " . $e->getMessage() . "</br>"; 
            $errorMsg[] = "There was a problem with accepting your data please."; 
        } 
        
        foreach ($results as $result){
            echo "<p>" . $result['pkEmail'] . " " . $result['fldFirstName'] . " " . $result['fldLastName'] . " " . $result['fldConfirmed'] . "</p><br>\n";
        }
?>