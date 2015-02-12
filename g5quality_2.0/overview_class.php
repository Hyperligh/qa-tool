<?php
class domain_overview {
    
    public $number_title_errors=0; //= $request->number_title_errors($code);
    public $number_loading_errors=0;// = $request->number_loading_errors_ajax($code);
    public $number_repetitive_errors=0;// = $request->number_repetitive_errors($code);
    public $number_text_errors=0;// = $request->number_text_errors($code);
    public $number_linking_issues=0;// = $request->number_linking_issues($code);
    public $number_spelling_errors=0;// =$request->number_spelling_errors($code);
    public $number_ga_errors=0;// = $request->number_ga_errors($code);
  
    
    
    
    public function total_errors($code){
                 
        
            if(isset($db) == false){
                require_once($_SERVER['DOCUMENT_ROOT'] . "/g5quality_2.0/Data/db_class.php");
                $db_class = new db_class();
                $db = $db_class->db_call();
            }
                
 
            $stmt = $db->prepare("SELECT
            sum(case when type = 'SpellingError' then 1 else 0 end ), 
            sum(case when type = 'TextError' then 1 else 0 end ),
            sum(case when type = 'LoadingError' then 1 else 0 end ),
            sum(case when type = 'RepetitiveText' then 1 else 0 end ), 
            sum(case when type = 'LinkingIssue' then 1 else 0 end ),
            sum(case when type = 'TitleError' then 1 else 0 end ),
            sum(case when type = 'GA_E' then 1 else 0 end )
            FROM errors WHERE Domain_Code = ?;");
            
             $stmt->bind_param('s', $code);
             $stmt->execute();
             $stmt->bind_result($spelling, $text, $loading, $repetitive, $linking, $title, $gae);
             //$stmt->store_result();
             $errors = 0;
             
    
             while($stmt->fetch()){
                $this->number_spelling_errors = $spelling;
                $this->number_repetitive_errors = $repetitive;
                $this->number_linking_issues = $linking;
                $this->number_text_errors = $text;
                $this->number_loading_errors = $loading;
                $this->number_title_errors = $title;
                $this->number_ga_errors = $gae;
                }
    }
    
}
    


?>