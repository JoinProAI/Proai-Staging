<?php
	error_reporting(E_ALL & ~E_WARNING);
	include_once "openai/autoload.php";
	include_once "includes/config.php";
	include_once "includes/function.php";
	require_once 'dompdf/autoload.inc.php';

	use Orhanerday\OpenAi\OpenAi;

	extract($_REQUEST);
	if(!isset($keyword)){
		exit;
	}

include_once "json.php";

$qry= "select * from openai_prompt where slug='".$keyword."' and user_for like'%".$user_for."%' order by rand() limit 1";
$result = $db->query($qry);

$array= array(
'html'=>'',
'val'=>'',
);

if(count($result)){
	$plain_prompt = $result[0]['prompt'];
	$prompt = $result[0]['prompt'];
	$words = listWords($prompt);

$open_ai = new OpenAi(OPEN_API_KEY);

if($keyword=='management_team_members' || $keyword =='management_team_gaps') {


		if($keyword=='management_team_members'){ 
				
				$final_prompt = '';
				$final_html ='';
				$final_val ='';
				foreach($team_members as $k => $team_member) {

					$tmp_prompt = $prompt;
					$tmp_prompt = str_replace('.0.','.'.$k.'.',$tmp_prompt);
	
	
				   $words = listWords($tmp_prompt);
					foreach($words as $word) {


						$match_with= grabJsonText($word, $output);
						$tmp_prompt = str_replace('{'.$word.'}', $match_with, $tmp_prompt);
					}										
						
						$final_prompt .=$tmp_prompt;

						$complete = $open_ai->completion([
									'model' => 'text-davinci-003',
									'prompt' => $tmp_prompt,
									'temperature' => 0.7,
									'max_tokens' => 500,
									'top_p'=>1,
									'frequency_penalty' => 0,
									'presence_penalty' => 0,
								]);

								$response = json_decode($complete, true);
								if(isset($response['choices'][0]['text'])) {
									$final_val .= $response['choices'][0]['text'];

 
								}

						$final_prompt .="\n\n\n\n";
						$final_val .="\n\n\n\n";

				}
								$array= array(
									'prompt'=>$plain_prompt,
									'prompt_build' =>nl2br($final_prompt),
									'html'=> nl2br($response['choices'][0]['text']),
									'val'=>$response['choices'][0]['text'],
									);


		}
		else if($keyword =='management_team_gaps') { 
				
				$final_prompt = '';
				$final_html ='';
				$final_val ='';
				foreach($team_gaps as $team_gap) {

					$tmp_prompt = $prompt;
					$words = listWords($tmp_prompt);
					foreach($words as $word) {
						$match_with= grabJsonText($word, $output);
						$tmp_prompt = str_replace('{'.$word.'}', $match_with, $tmp_prompt);
					}										
					$final_prompt .=$tmp_prompt;

						$complete = $open_ai->completion([
									'model' => 'text-davinci-003',
									'prompt' => $tmp_prompt,
									'temperature' => 0.7,
									'max_tokens' => 500,
									'top_p'=>1,
									'frequency_penalty' => 0,
									'presence_penalty' => 0,
								]);

								$response = json_decode($complete, true);
								if(isset($response['choices'][0]['text'])) {
									$final_val .= $response['choices'][0]['text'];

 
								}

						$final_prompt .="\n\n\n\n";
						$final_val .="\n\n\n\n";

				}


					$array= array(
						'prompt'=>trim($plain_prompt),
						'prompt_build' =>nl2br(trim($final_prompt)),
						'html'=> nl2br(trim($response['choices'][0]['text'])),
						'val'=>trim($response['choices'][0]['text']),
						);
		}


}
else {


			$tmp_prompt = $prompt;
			foreach($words as $word) {
				$match_with= grabJsonText($word, $output);
				$tmp_prompt = str_replace('{'.$word.'}', $match_with, $tmp_prompt);
			}				
			$final_prompt = $tmp_prompt;
		
		$complete = $open_ai->completion([
			'model' => 'text-davinci-003',
			'prompt' => $final_prompt,
			'temperature' => 0.7,
			'max_tokens' => 500,
			'top_p'=>1,
			'frequency_penalty' => 0,
			'presence_penalty' => 0,
		]);

		$response = json_decode($complete, true);
		if(isset($response['choices'][0]['text'])) {
			$array= array(
				'prompt'=>trim($prompt),
				'prompt_build' =>nl2br(trim($final_prompt)),
				'html'=> nl2br($response['choices'][0]['text']),
				'val'=>$response['choices'][0]['text'],
			);
		}
	}
}
echo json_encode($array);


 

?>