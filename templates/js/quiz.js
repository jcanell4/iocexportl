define (["functions","render"],function(func,render){
	var checkquiz = function(e,func){
	  var target = jQuery(e);
	  var form = target.parents('form');
	  var quiz = form.parent();
	  var div = quiz.parent();
	  var quiztype = form.find("input[name='qtype']").val();
	  var numitems = form.find("input[name='qnum']").val();
	  var solution = form.find("input[name='qsol']").val();
	  var solutions = solution.split(',');
	  var ok = false;
	  
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
		  res = '<p class="ok">@IOCOK@</p>';
		  ok = true;
		  func.editCheckExercise(document.location.pathname,div.prev('h2').children('a').attr('id'));
		}else{
		  res = '<p class="ko">@IOCWRONG@</p>';
		  ok = false;
		}
	  }else{
		  var resp = values.join(',');
		  if (solution == resp){
			res = '<p class="ok">@IOCOK@</p>';
			ok = true;
			func.editCheckExercise(document.location.pathname,div.prev('h2').children('a').attr('id'));
		  }else{
			res = '<p class="ko">@IOCWRONG@</p>';
			ok = false;
		  }
	  }
	  showsolution(target, res, ok);
	}

	var checkquiz2 = function(e){
	  var target = jQuery(e);
	  var form = target.parents('form');
	  var quiz = form.parent();
	  var div = quiz.parent();
	  var solutions = [];
	  var ok = false;
	  
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
			  resp = '<p class="ko">@IOCWRONG@</p>';
			  ok = false;
			  break;
		  }
	  }
	  if (resp == ''){
		  resp = '<p class="ok">@IOCOK@</p>';
		  ok = true;
		  func.editCheckExercise(document.location.pathname,div.prev('h2').children('a').attr('id'));
	  }
	  showsolution(target, resp, ok);
	} 

	var showsolution = function(target, text, ok){
	  var form = target.parents('form');
	  var quiz = target.parents('div');
	  
	  if (quiz.is('div.quiz')){
		if (ok){
			jQuery(form).parent().children(".quiz_result").removeClass("quiz_ko").addClass("quiz_ok");
		}else{
			jQuery(form).parent().children(".quiz_result").removeClass("quiz_ok").addClass("quiz_ko");
		}
		jQuery(form).parent().children(".quiz_result").hide().fadeOut("slow").html(text).fadeIn("slow");
	  }else{
		alert(text);
	  }
	} 

	var showsol = function(target,render){
		var form = jQuery(target).parents('form');
		if (jQuery(form).children(".solution").css('display') == 'block' ){
			jQuery(form).children(".solution").slideUp("slow");
			jQuery(target).attr('value','@IOCSHOW@');
		}else{
			jQuery(form).children(".solution").slideDown("slow");
			jQuery(target).attr('value','@IOCHIDE@');
			jQuery('article').css('height','auto');
			render.infoTable();
			render.infoFigure();
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
	jQuery('.btn_solution').on("click",function (){
		checkquiz(this,func);
	});
	
	jQuery('.btn_solution2').on("click",function (){
		checkquiz2(this);
	});
	
	jQuery('.btn_solution3').on("click",function (){
		showsol(this,render);
	});
	
	return this;
});