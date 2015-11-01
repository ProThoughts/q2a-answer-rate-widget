<?php

/*
	Question2Answer Plugin: Answer Rate Widget
*/

class qa_answer_rate_widget {
	
	function allow_template($template)
	{
		$allow=false;
		
		switch ($template)
		{
			case 'activity':
			case 'qa':
			case 'questions':
			case 'hot':
			case 'ask':
			case 'categories':
			case 'question':
			case 'tag':
			case 'tags':
			case 'unanswered':
			case 'user':
			case 'users':
			case 'search':
			case 'admin':
				$allow=true;
				break;
		}
		
		return $allow;
	}
	
	function allow_region($region)
	{
		$allow=false;
		
		switch ($region)
		{
			case 'main':
			case 'side':
				$allow=true;
				break;
			case 'full':					
				break;
		}
		
		return $allow;
	}

	function output_widget($region, $place, $themeobject, $template, $request, $qa_content)
	{
		// title
		$themeobject->output('<div id="answerrate"><h2>'.qa_lang_html('qa_answer_rate_widget_lang/answer_rate_events').'</h2>');

		// question count
		$q_count = 0;
		$sql = "select count(postid) as q_count from qa_posts where type='Q'";
		$q_result = qa_db_query_sub($sql);
		while ( ($result = qa_db_read_one_assoc($q_result,true)) !== null ) {
			$q_count = $result['q_count'];
		}

		// question count of with answer
		$a_count = 0;
		$sql = "select count(postid) as a_count from (select * from  (select postid,type from qa_posts where type='Q') t1";
		$sql .= " left join (select parentid from qa_posts where type='A' group by(parentid)) t2";
		$sql .= " on t1.postid = t2.parentid) t0  where parentid is not null";
		$a_result = qa_db_query_sub($sql);
		while ( ($result = qa_db_read_one_assoc($a_result,true)) !== null ) {
			$a_count = $result['a_count'];
		}

		// answer rate
		$answer_rate = 0;
		if ($q_count > 0) {
			$answer_rate = round($a_count * 100 / $q_count);
		}

		$themeobject->output('<p class="qa-activity-count-item">'. qa_lang_html('qa_answer_rate_widget_lang/answer_rate'));
		$themeobject->output('<span class="qa-activity-count-data">　　' . $answer_rate . '%</span></p>');

		// get average time
		$sql = "select timediff(t2.created,t1.created) as dftime from";
		$sql .= " (select postid,userid,created from qa_posts where type='Q' order by postid desc) t1 inner join";
		$sql .= " (select postid,parentid,created from qa_posts where type='A' group by (parentid)";
		$sql .= " order by parentid desc) t2 on t1.postid=t2.parentid limit 20";
		$result = qa_db_query_sub($sql);
		$posts = qa_db_read_all_assoc($result);
		$ttlsec = 0;
		$count = 0;
		foreach($posts as $post) {
			$xtime = explode(":", $post['dftime']);
			$ttlsec += $xtime[0] * 3600 + $xtime[1] * 60 + $xtime[2];
			$count++;
			if ($count >= 20) {
				break;
			}
		}

		$hh = 0;
		$mm = 0;
		if ($count > 0) {
			$avgtime = round($ttlsec / $count);
			$hh = intval($avgtime / 3600);
			$mm = intval(($avgtime - ($hh * 3600)) / 60);
			$dd = $avgtime - ($hh * 3600) - ($mm * 60);
			if ($dd > 0) {
				$mm++;
			}
		}

		$average_time = $hh."時間".$mm."分";

		$themeobject->output('<p class="qa-activity-count-item">'. qa_lang_html('qa_answer_rate_widget_lang/average_time'));
		$themeobject->output('<br><span class="qa-activity-count-data">　　' . $average_time . '</span></p>');

	}
}
/*
	Omit PHP closing tag to help avoid accidental output
*/
