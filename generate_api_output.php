<?php
ignore_user_abort(false);
set_time_limit(0);
error_reporting(E_ALL & ~E_WARNING);
include_once "openai/autoload.php";
include_once "includes/config.php";
include_once "includes/function.php";
require_once 'dompdf/autoload.inc.php';

use Orhanerday\OpenAi\OpenAi;
	
	extract($_REQUEST);

	include_once "json.php";
	
	$open_ai = new OpenAi(OPEN_API_KEY);

$main_items = array('Executive Summary','Company Description','Industry Overview','Competitive Comparison','Strategy & Implementation Summary','Marketing Plan');

foreach($main_items as $main_title) {

	$db->where('main_title',$main_title);
	$db->orderby('auto_id','asc');
	$keywords_list= $db->get('openai_prompt');
	
	echo "<h2>".$main_title."</h2>";

	foreach($keywords_list as $row){

		echo "<h3>".$row['name']."</h3>";
		$keyword = $row['slug'];
		$qry= "select * from openai_prompt where slug='".$keyword."' and user_for like'%-".$user_for."-%' order by rand() limit 1";
		$result = $db->query($qry);
		$array= array(
		'html'=>'',
		'val'=>'',
		);

				if(count($result)){

					$plain_prompt = $result[0]['prompt'];
					$prompt = $result[0]['prompt'];
					$words = listWords($prompt);

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

				usleep(70000);

				echo "<b>Prompt:</b> ". $array['prompt'];
				echo "<BR><BR>";
				echo "<b>Prompt Build:</b> ". $array['prompt_build'];
				echo "<BR><BR>";

				echo "<b>API output:</b> ";
				echo "<pre>";
				echo $array['val'];
				echo "</pre>";
				echo "<BR><BR>";
				echo "<hr />";
				echo "<BR><BR><BR><BR>";
				ob_flush();
				flush();
	}

}

	echo "<h1>ALL PROMPT GENERATED</h1>";
?>