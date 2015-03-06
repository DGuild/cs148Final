<?php
    
function sqlSelect($sql){
        global $db;
        try { 
            if ($db->inTransaction){
                $db->beginTransaction();
            }
            $stmt = $db->prepare($sql);  
            $stmt->execute();
            $results = $stmt -> fetchAll(PDO::FETCH_ASSOC);
            return $results;
            $db->commit();
        } catch (PDOExecption $e) { 
            $db->rollback(); 
            if ($debug) print "Error!: " . $e->getMessage() . "</br>"; 
            $errorMsg[] = "There was a problem with accepting your data please.";
                return $errorMsg; 
        } 
    }
    
function sqlUpdate($sql){
        global $db;
        try { 
            $db->beginTransaction(); 
            $stmt = $db->prepare($sql);  
            $stmt->execute();
            $db->commit();
        } catch (PDOExecption $e) { 
            $db->rollback(); 
            if ($debug) print "Error!: " . $e->getMessage() . "</br>"; 
            $errorMsg[] = "There was a problem with accepting your data please.";
                return $errorMsg; 
        } 
    }
    
function sqlInsert($sql){
        global $db;
        try { 
            if ($db->inTransaction){
                $db->beginTransaction(); 
            }
            $stmt = $db->prepare($sql);  
            $stmt->execute();
            $entryID = $db->lastInsertId();
            return $entryID;
            $db->commit();
        } catch (PDOExecption $e) { 
            $db->rollback(); 
            if ($debug) print "Error!: " . $e->getMessage() . "</br>"; 
            $errorMsg[] = "There was a problem with accepting your data please.";
                return $errorMsg; 
        } 
    }
    
function getExcerpt($text, $num)
{
    $words_in_text = str_word_count($text,1);
    $words_to_return = $num;
    $result = array_slice($words_in_text,0,$words_to_return);
    return '<p>'.implode(" ",$result).'</p>';
}
?>