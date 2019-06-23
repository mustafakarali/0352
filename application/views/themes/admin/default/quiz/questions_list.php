<!--Load JS-->
<?=load_inline_js('inc/js-select_all'); ?>

<script>
    window.quizListUrl = '<?=site_url($BC->_getBaseURI())?>';
</script>
<?=include_js($BC->_getFolder('js').'custom/quiz/admin/copy-questions.js')?>
<!--Load JS-->

<?=br(2)?>

<? $quiz_id = $this->uri->segment($BC->_getSegmentsOffset()+3) ?>

<p>
	<?=anchor($BC->_getBaseURI(),language('quiz_list'))?> 
	<?if(userAccess($BC->_getController(),'edit')):?>
		| <?=anchor($BC->_getBaseURI().'/edit/id/asc/0/'.$quiz_id,language('edit_quiz_info'))?>
	<?endif?>
</p>

<?if(userAccess($BC->_getController(),'add')):?>
	<p><?=anchor($BC->_getBaseURI().'/questions_add/'.$quiz_id,language('add_question'))?></p>
<?endif?>

<br />

<?if(!empty($questions)):?>

    <?//link for delete selected records?>
    <p>
        <?=anchor__Delete_Selected()?>
        | <a href="#" id="copy-questions"><?=language('copy')?> <?=language('question')?></a>
    </p>
    
    <?//lopen form for delete records?>
    <?=form_open($BC->_getBaseURI()."/delete_selected_questions/".$quiz_id,array('name'=>'form'))?>
    
    <?//set ouput format
    $cols = array(
        array(
            'field' => 'id',
            'width' => 50,
            'title' => 'ID'
        ),
        array(
            'field'=>'question',
            'just_text'=>TRUE
        ),
        array(
            'field'=>'question_type',
            'just_text'=>TRUE,
            'width'=>70
        ),
        array(
            'field'=>'answers_count',
            'width'=>50,
            'title'=>'Answers'
        ),
        array(
            'field'=>'connected_answers_count',
            'width'=>50,
            'title'=>'Connect'
        ),
        array(
            'field'=>'correct_answers_count',
            'width'=>50,
            'title'=>'Correct'
        ),
        array(
            'field'=>'question_edit',
            'title'=>' ',
            'width'=>50
        )
    );
    
    $rows = $questions;
    
    //prepare rows for output
    foreach ($rows as &$row)
    {
        $row['question__output'] = anchor($BC->_getBaseURI().'/answers_list/'.$quiz_id.'/'.$row['id'],htmlspecialchars($row['question']));

        $answers = $BC->quiz_model->getAnswers($row['id']);
        $correct_answers = $BC->quiz_model->getCorrectAnswers($row['id']);
        $connected_answers = $BC->quiz_model->getConnectedAnswers($row['id']);
        $row['question_type'] = $BC->quiz_model->getQuestionType($row['id'], $answers, $correct_answers);

        $row['answers_count'] = count($answers);
        if($row['question_type'] === 'multi-radio') {
            $row['connected_answers_count'] = count($connected_answers);
        }
        if($row['question_type'] !== 'multi-radio') {
            $row['correct_answers_count'] = count($correct_answers);
        }

        $row['question_edit__output'] = anchor_admin('questions_edit',$row['id']);
    }
    
    show_records_table($cols,$rows,FALSE,FALSE);
    ?>
    
    </form>

<?endif;?>