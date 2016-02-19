	var checkquiz = function(e){
	  var target = jQuery(e);
	  var form = target.parents('form');
	  var quiz = form.parent();
	  var quiztype = form.find("input[name='qtype']").val();
	  var numitems = form.find("input[name='qnum']").val();
	  var solution = form.find("input[name='qsol']").val();
	  var solutions = solution.split(',');
	  
	  if (quiztype == 'vf'){
		var values = [];
	
		form.find('td.ko').removeClass('ko');
		form.find('td.ok').removeClass('ok');
		for (var i=1; i<=numitems; i++){
		  var radio = form.find('input[name="sol_' + i + '"]:checked');
		  var value = radio.val();
		  if(value == solutions[i-1]){
		    radio.parent().addClass('ok');
		    values.push(value);
		  }else{
		    radio.parent().addClass('ko');
		    values.push('');
		  }
		}
	  }else if(quiztype == 'choice'){
		var values = true;
	
		form.find('td.ko').removeClass('ko');
		form.find('td.ok').removeClass('ok');
		for (var i=1; i<=numitems; i++){
		  var checkbox = form.find('input[name="sol_' + i + '"]');
		  
		  if(checkbox.is(':checked')){
		    if(inArray(i, solutions)){
		      checkbox.parent().addClass('ok');
		    }else{
		      checkbox.parent().addClass('ko');
		  	  values = false;
		    }
		  }else{
			  if(!inArray(i, solutions)){
		        checkbox.parent().addClass('ok');
		      }else{
		        checkbox.parent().addClass('ko');
		        values = false;
		      }
		  }
		}
	  }
	  var res = '';
	  
	  if(quiztype !== 'vf'){
		if (values){
		  res = '<p class="ok">Correcte</p>';
		}else{
		  res = '<p class="ko">Erroni</p>';
		}
	  }else{
		  var resp = values.join(',');
		  if (solution == resp){
			res = '<p class="ok">Correcte</p>';
		  }else{
			res = '<p class="ko">Erroni</p>';
		  }
	  }
	  showsolution(target, res);
	}

	var checkquiz2 = function(e){
	  var target = jQuery(e);
	  var form = target.parents('form');
	  var solutions = [];
	  
	  form.find('select').each(function(){
		  var select = jQuery(this);
		  select.find('option:selected').each(function(){
			  var option = jQuery(this);
			  if (option.attr('value') == select.attr('name')){
				  select.removeClass('ko');
				  select.addClass('ok');
				  //select.next().find('img').attr('src','../../css/img/ok.png');
				  solutions.push('V');
			  }else{
				  select.removeClass('ok');
				  select.addClass('ko');
				  //select.next().find('img').attr('src','../../css/img/error.png');
				  solutions.push('F');
			  }
		  });
	  });
	  var resp = '';
	  for(i=0, l=solutions.length; i<l; i++){
		  if (solutions[i] == 'F'){
			  resp = '<p class="ko">Erroni</p>';
			  break;
		  }
	  }
	  if (resp == '') resp = '<p class="ok">Correcte</p>';
	  showsolution(target, resp);
	} 

	var showsolution = function(target, text){
	  var form = target.parents('form');
	  var quiz = target.parents('div');
	  
	  if (quiz.is('div.quiz')){
		jQuery(form).parent().children(".quiz_result").hide().fadeOut("slow").html(text).fadeIn("slow");
	  }else{
		alert(text);
	  }
	} 

	var showsol = function(target){
		var form = jQuery(target).parents('form');
		if (jQuery(form).children(".solution").css('display') == 'block' ){
			jQuery(form).children(".solution").hide("slow");
			jQuery(target).attr('value','Mostra');
		}else{
			jQuery(form).children(".solution").show("slow");
			jQuery(target).attr('value','Oculta');
		}
	}

	var inArray = function(needle, haystack){
		for(var key in haystack)
		{
			needle = needle + '';//to String
		    if(needle === haystack[key])
		    {
		        return true;
		    }
		}
		return false;
	}
	
jQuery(document).ready(function(){	
	jQuery('.btn_solution').click(function (){
		checkquiz(this);
	});
	
	jQuery('.btn_solution2').click(function (){
		checkquiz2(this);
	});
	
	jQuery('.btn_solution3').click(function (){
		showsol(this);
	});
});