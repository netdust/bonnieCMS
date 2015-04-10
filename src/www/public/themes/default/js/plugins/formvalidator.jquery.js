/**
 * jQuery Form Validator
 * ------------------------------------------
 * Created by Victor Jonsson <http://www.victorjonsson.se>
 *
 * @website http://formvalidator.net/
 * @license Dual licensed under the MIT or GPL Version 2 licenses
 * @version 2.1.47
 */
(function($){"use strict";var _applyErrorStyle=function($elem,conf){$elem.addClass(conf.errorElementClass).removeClass("valid").parent().addClass("has-error").removeClass("has-success");if(conf.borderColorOnError!==""){$elem.css("border-color",conf.borderColorOnError)}},_removeErrorStyle=function($elem,conf){$elem.each(function(){_setInlineErrorMessage($(this),"",conf,conf.errorMessagePosition);$(this).removeClass("valid").removeClass(conf.errorElementClass).css("border-color","").parent().removeClass("has-error").removeClass("has-success").find("."+conf.errorMessageClass).remove()})},_setInlineErrorMessage=function($input,mess,conf,$messageContainer){var custom=_getInlineErrorElement($input);if(custom){custom.innerHTML=mess}else if(typeof $messageContainer=="object"){var $found=false;$messageContainer.find("."+conf.errorMessageClass).each(function(){if(this.inputReferer==$input[0]){$found=$(this);return false}});if($found){if(!mess){$found.remove()}else{$found.html(mess)}}else{var $mess=$('<div class="'+conf.errorMessageClass+'">'+mess+"</div>");$mess[0].inputReferer=$input[0];$messageContainer.prepend($mess)}}else{var $mess=$input.parent().find("."+conf.errorMessageClass+".help-block");if($mess.length==0){$mess=$("<span></span>").addClass("help-block").addClass(conf.errorMessageClass);$mess.appendTo($input.parent())}$mess.html(mess)}},_getInlineErrorElement=function($input,conf){return document.getElementById($input.attr("name")+"_err_msg")};$.fn.validateOnBlur=function(language,settings){this.find("input[data-validation],textarea[data-validation],select[data-validation]").bind("blur.validation",function(){$(this).validateInputOnBlur(language,settings)});return this};$.fn.validateOnEvent=function(language,settings){this.find("input[data-validation][data-validation-event],textarea[data-validation][data-validation-event],select[data-validation][data-validation-event]").each(function(){var $el=$(this),etype=$el.attr("data-validation-event");if(etype){$el.bind(etype+".validation",function(){$(this).validateInputOnBlur(language,settings,false,etype)})}});return this};$.fn.showHelpOnFocus=function(attrName){if(!attrName){attrName="data-validation-help"}this.find(".has-help-txt").valAttr("has-keyup-event",false).valAttr("backend-valid",false).valAttr("backend-invalid",false).removeClass("has-help-txt");this.find("textarea,input").each(function(){var $elem=$(this),className="jquery_form_help_"+($elem.attr("name")||"").replace(/(:|\.|\[|\])/g,""),help=$elem.attr(attrName);if(help){$elem.addClass("has-help-txt").unbind("focus.help").bind("focus.help",function(){var $help=$elem.parent().find("."+className);if($help.length==0){$help=$("<span />").addClass(className).addClass("help").addClass("help-block").text(help).hide();$elem.after($help)}$help.fadeIn()}).unbind("blur.help").bind("blur.help",function(){$(this).parent().find("."+className).fadeOut("slow")})}});return this};$.fn.validateInputOnBlur=function(language,conf,attachKeyupEvent,eventContext){if(attachKeyupEvent===undefined)attachKeyupEvent=true;if(!eventContext)eventContext="blur";if((this.valAttr("suggestion-nr")||this.valAttr("postpone")||this.hasClass("hasDatepicker"))&&!window.postponedValidation){var _self=this,postponeTime=this.valAttr("postpone")||200;window.postponedValidation=function(){_self.validateInputOnBlur(language,conf,attachKeyupEvent);window.postponedValidation=false};setTimeout(function(){if(window.postponedValidation){window.postponedValidation()}},postponeTime);return this}language=$.extend({},$.formUtils.LANG,language||{});_removeErrorStyle(this,conf);var $elem=this,$form=$elem.closest("form"),validationRule=$elem.attr(conf.validationRuleAttribute),validation=$.formUtils.validateInput($elem,language,$.extend({},conf,{errorMessagePosition:"element"}),$form,eventContext);$elem.trigger("validation",[validation===null?null:validation===true]);if(validation===true){$elem.addClass("valid").parent().addClass("has-success")}else if(validation!==null){_applyErrorStyle($elem,conf);_setInlineErrorMessage($elem,validation,conf,conf.errorMessagePosition);if(attachKeyupEvent){$elem.bind("keyup",function(){$(this).validateInputOnBlur(language,conf,false,"keyup")})}}return this};$.fn.valAttr=function(name,val){if(val===undefined){return this.attr("data-validation-"+name)}else if(val===false||val===null){return this.removeAttr("data-validation-"+name)}else{if(name.length>0)name="-"+name;return this.attr("data-validation"+name,val)}};$.fn.validateForm=function(language,conf){language=$.extend({},$.formUtils.LANG,language||{});$.formUtils.isValidatingEntireForm=true;$.formUtils.haltValidation=false;var addErrorMessage=function(mess,$elem){if(mess!==null){if($.inArray(mess,errorMessages)<0){errorMessages.push(mess)}errorInputs.push($elem);$elem.attr("current-error",mess);_applyErrorStyle($elem,conf)}},errorMessages=[],errorInputs=[],$form=this,ignoreInput=function(name,type){if(type==="submit"||type==="button"||type=="reset"){return true}return $.inArray(name,conf.ignore||[])>-1};$form.find("."+conf.errorMessageClass+".alert").remove();_removeErrorStyle($form.find("."+conf.errorElementClass+",.valid"),conf);$form.find("input,textarea,select").filter(':not([type="submit"],[type="button"])').each(function(){var $elem=$(this);var elementType=$elem.attr("type");if(!ignoreInput($elem.attr("name"),elementType)){var validation=$.formUtils.validateInput($elem,language,conf,$form,"submit");$elem.trigger("validation",[validation===true]);if(validation!==true){addErrorMessage(validation,$elem)}else{$elem.valAttr("current-error",false).addClass("valid").parent().addClass("has-success")}}});if(typeof conf.onValidate=="function"){var errors=conf.onValidate($form);if($.isArray(errors)){$.each(errors,function(i,err){addErrorMessage(err.message,err.element)})}else if(errors&&errors.element&&errors.message){addErrorMessage(errors.message,errors.element)}}if(!$.formUtils.haltValidation&&errorInputs.length>0){$.formUtils.isValidatingEntireForm=false;if(conf.errorMessagePosition==="top"){var messages="<strong>"+language.errorTitle+"</strong>";$.each(errorMessages,function(i,mess){messages+="<br />* "+mess});$form.children().eq(0).before('<div class="'+conf.errorMessageClass+' alert alert-danger">'+messages+"</div>")}else{$.each(errorInputs,function(i,$input){_setInlineErrorMessage($input,$input.attr("current-error"),conf,conf.errorMessagePosition)})}if(conf.scrollToTopOnError){$(window).scrollTop($form.offset().top-20)}return false}$.formUtils.isValidatingEntireForm=false;return!$.formUtils.haltValidation};$.fn.restrictLength=function(maxLengthElement){new $.formUtils.lengthRestriction(this,maxLengthElement);return this};$.fn.addSuggestions=function(settings){var sugs=false;this.find("input").each(function(){var $field=$(this);sugs=$.split($field.attr("data-suggestions"));if(sugs.length>0&&!$field.hasClass("has-suggestions")){$.formUtils.suggest($field,sugs,settings);$field.addClass("has-suggestions")}});return this};$.split=function(val,func,delim){if(typeof func!="function"){if(!val)return[];var values=[];$.each(val.split(func?func:","),function(i,str){str=$.trim(str);if(str.length)values.push(str)});return values}else if(val){if(!delim)delim=",";$.each(val.split(delim),function(i,str){str=$.trim(str);if(str.length)return func(str,i)})}};$.validate=function(conf){var defaultConf=$.extend($.formUtils.defaultConfig(),{form:"form",validateOnEvent:true,validateOnBlur:true,showHelpOnFocus:true,addSuggestions:true,modules:"",onModulesLoaded:null,language:false,onSuccess:false,onError:false});conf=$.extend(defaultConf,conf||{});$.split(conf.form,function(formQuery){var $form=$(formQuery);$form.find(".has-help-txt").unbind("focus.validation").unbind("blur.validation");$form.removeClass("has-validation-callback").unbind("submit.validation").unbind("reset.validation").find("input[data-validation],textarea[data-validation]").unbind("blur.validation");$form.bind("submit.validation",function(){var $form=$(this);if($.formUtils.isLoadingModules){setTimeout(function(){$form.trigger("submit.validation")},200);return false}var valid=$form.validateForm(conf.language,conf);if(valid&&typeof conf.onSuccess=="function"){var callbackResponse=conf.onSuccess($form);if(callbackResponse===false)return false}else if(!valid&&typeof conf.onError=="function"){conf.onError($form);return false}else{return valid}}).bind("reset.validation",function(){$(this).find("."+conf.errorMessageClass+".alert").remove();_removeErrorStyle($(this).find("."+conf.errorElementClass+",.valid"),conf)}).addClass("has-validation-callback");if(conf.showHelpOnFocus){$form.showHelpOnFocus()}if(conf.addSuggestions){$form.addSuggestions()}if(conf.validateOnBlur){$form.validateOnBlur(conf.language,conf)}if(conf.validateOnEvent){$form.validateOnEvent(conf.language,conf)}});if(conf.modules!=""){if(typeof conf.onModulesLoaded=="function"){$.formUtils.on("load",function(){conf.onModulesLoaded()})}$.formUtils.loadModules(conf.modules)}};$.formUtils={defaultConfig:function(){return{ignore:[],errorElementClass:"error",borderColorOnError:"red",errorMessageClass:"form-error",validationRuleAttribute:"data-validation",validationErrorMsgAttribute:"data-validation-error-msg",errorMessagePosition:"element",scrollToTopOnError:true,dateFormat:"yyyy-mm-dd",addValidClassOnAll:false,decimalSeparator:"."}},validators:{},_events:{load:[],valid:[],invalid:[]},haltValidation:false,isValidatingEntireForm:false,addValidator:function(validator){var name=validator.name.indexOf("validate_")===0?validator.name:"validate_"+validator.name;if(validator.validateOnKeyUp===undefined)validator.validateOnKeyUp=true;this.validators[name]=validator},on:function(evt,callback){if(this._events[evt]===undefined)this._events[evt]=[];this._events[evt].push(callback)},trigger:function(evt,argA,argB){$.each(this._events[evt]||[],function(i,func){func(argA,argB)})},isLoadingModules:false,loadedModules:{},loadModules:function(modules,path,fireEvent){if(fireEvent===undefined)fireEvent=true;if($.formUtils.isLoadingModules){setTimeout(function(){$.formUtils.loadModules(modules,path,fireEvent)});return}var hasLoadedAnyModule=false,loadModuleScripts=function(modules,path){var moduleList=$.split(modules),numModules=moduleList.length,moduleLoadedCallback=function(){numModules--;if(numModules==0){$.formUtils.isLoadingModules=false;if(fireEvent&&hasLoadedAnyModule){$.formUtils.trigger("load",path)}}};if(numModules>0){$.formUtils.isLoadingModules=true}var cacheSuffix="?__="+(new Date).getTime(),appendToElement=document.getElementsByTagName("head")[0]||document.getElementsByTagName("body")[0];$.each(moduleList,function(i,modName){modName=$.trim(modName);if(modName.length==0){moduleLoadedCallback()}else{var scriptUrl=path+modName+(modName.substr(-3)==".js"?"":".js"),script=document.createElement("SCRIPT");if(scriptUrl in $.formUtils.loadedModules){moduleLoadedCallback()}else{$.formUtils.loadedModules[scriptUrl]=1;hasLoadedAnyModule=true;script.type="text/javascript";script.onload=moduleLoadedCallback;script.src=scriptUrl+(scriptUrl.substr(-7)==".dev.js"?cacheSuffix:"");script.onreadystatechange=function(){if(this.readyState=="complete"){moduleLoadedCallback()}};appendToElement.appendChild(script)}}})};if(path){loadModuleScripts(modules,path)}else{var findScriptPathAndLoadModules=function(){var foundPath=false;$("script").each(function(){if(this.src){var scriptName=this.src.substr(this.src.lastIndexOf("/")+1,this.src.length);if(scriptName.indexOf("jquery.form-validator.js")>-1||scriptName.indexOf("jquery.form-validator.min.js")>-1){foundPath=this.src.substr(0,this.src.lastIndexOf("/"))+"/";if(foundPath=="/")foundPath="";return false}}});if(foundPath!==false){loadModuleScripts(modules,foundPath);return true}return false};if(!findScriptPathAndLoadModules()){$(findScriptPathAndLoadModules)}}},validateInput:function($elem,language,conf,$form,eventContext){if($elem.attr("disabled"))return null;$elem.trigger("beforeValidation");var value=$.trim($elem.val()||""),optional=$elem.valAttr("optional"),validationDependsOnCheckedInput=false,validationDependentInputIsChecked=false,validateIfCheckedElement=false,validateIfCheckedElementName=$elem.valAttr("if-checked");if(validateIfCheckedElementName!=null){validationDependsOnCheckedInput=true;validateIfCheckedElement=$form.find('input[name="'+validateIfCheckedElementName+'"]');if(validateIfCheckedElement.prop("checked")){validationDependentInputIsChecked=true}}if(!value&&optional==="true"||validationDependsOnCheckedInput&&!validationDependentInputIsChecked){return conf.addValidClassOnAll?true:null}var validationRules=$elem.attr(conf.validationRuleAttribute),validationErrorMsg=true;if(!validationRules){return conf.addValidClassOnAll?true:null}$.split(validationRules,function(rule){if(rule.indexOf("validate_")!==0){rule="validate_"+rule}var validator=$.formUtils.validators[rule];if(validator&&typeof validator["validatorFunction"]=="function"){if(rule=="validate_checkbox_group"){$elem=$("[name='"+$elem.attr("name")+"']:eq(0)")}var isValid=true;if(eventContext!="keyup"||validator.validateOnKeyUp){isValid=validator.validatorFunction(value,$elem,conf,language,$form)}if(!isValid){validationErrorMsg=$elem.attr(conf.validationErrorMsgAttribute);if(!validationErrorMsg){validationErrorMsg=language[validator.errorMessageKey];if(!validationErrorMsg)validationErrorMsg=validator.errorMessage}return false}}else{console.warn('Using undefined validator "'+rule+'"')}}," ");if(typeof validationErrorMsg=="string"){return validationErrorMsg}else{return true}},parseDate:function(val,dateFormat){var divider=dateFormat.replace(/[a-zA-Z]/gi,"").substring(0,1),regexp="^",formatParts=dateFormat.split(divider),matches,day,month,year;$.each(formatParts,function(i,part){regexp+=(i>0?"\\"+divider:"")+"(\\d{"+part.length+"})"});regexp+="$";matches=val.match(new RegExp(regexp));if(matches===null){return false}var findDateUnit=function(unit,formatParts,matches){for(var i=0;i<formatParts.length;i++){if(formatParts[i].substring(0,1)===unit){return $.formUtils.parseDateInt(matches[i+1])}}return-1};month=findDateUnit("m",formatParts,matches);day=findDateUnit("d",formatParts,matches);year=findDateUnit("y",formatParts,matches);if(month===2&&day>28&&(year%4!==0||year%100===0&&year%400!==0)||month===2&&day>29&&(year%4===0||year%100!==0&&year%400===0)||month>12||month===0){return false}if(this.isShortMonth(month)&&day>30||!this.isShortMonth(month)&&day>31||day===0){return false}return[year,month,day]},parseDateInt:function(val){if(val.indexOf("0")===0){val=val.replace("0","")}return parseInt(val,10)},isShortMonth:function(m){return m%2===0&&m<7||m%2!==0&&m>7},lengthRestriction:function($inputElement,$maxLengthElement){var maxChars=parseInt($maxLengthElement.text(),10),charsLeft=0,countCharacters=function(){var numChars=$inputElement.val().length;if(numChars>maxChars){var currScrollTopPos=$inputElement.scrollTop();$inputElement.val($inputElement.val().substring(0,maxChars));$inputElement.scrollTop(currScrollTopPos)}charsLeft=maxChars-numChars;if(charsLeft<0)charsLeft=0;$maxLengthElement.text(charsLeft)};$($inputElement).bind("keydown keyup keypress focus blur",countCharacters).bind("cut paste",function(){setTimeout(countCharacters,100)});$(document).bind("ready",countCharacters)},numericRangeCheck:function(value,rangeAllowed){var range=$.split(rangeAllowed,"-");var minmax=parseInt(rangeAllowed.substr(3),10);if(range.length==2&&(value<parseInt(range[0],10)||value>parseInt(range[1],10))){return["out",range[0],range[1]]}else if(rangeAllowed.indexOf("min")===0&&value<minmax){return["min",minmax]}else if(rangeAllowed.indexOf("max")===0&&value>minmax){return["max",minmax]}else{return["ok"]}},_numSuggestionElements:0,_selectedSuggestion:null,_previousTypedVal:null,suggest:function($elem,suggestions,settings){var conf={css:{maxHeight:"150px",background:"#FFF",lineHeight:"150%",textDecoration:"underline",overflowX:"hidden",overflowY:"auto",border:"#CCC solid 1px",borderTop:"none",cursor:"pointer"},activeSuggestionCSS:{background:"#E9E9E9"}},setSuggsetionPosition=function($suggestionContainer,$input){var offset=$input.offset();$suggestionContainer.css({width:$input.outerWidth(),left:offset.left+"px",top:offset.top+$input.outerHeight()+"px"})};if(settings)$.extend(conf,settings);conf.css["position"]="absolute";conf.css["z-index"]=9999;$elem.attr("autocomplete","off");if(this._numSuggestionElements===0){$(window).bind("resize",function(){$(".jquery-form-suggestions").each(function(){var $container=$(this),suggestID=$container.attr("data-suggest-container");setSuggsetionPosition($container,$(".suggestions-"+suggestID).eq(0))})})}this._numSuggestionElements++;var onSelectSuggestion=function($el){var suggestionId=$el.valAttr("suggestion-nr");$.formUtils._selectedSuggestion=null;$.formUtils._previousTypedVal=null;$(".jquery-form-suggestion-"+suggestionId).fadeOut("fast")};$elem.data("suggestions",suggestions).valAttr("suggestion-nr",this._numSuggestionElements).unbind("focus.suggest").bind("focus.suggest",function(){$(this).trigger("keyup");$.formUtils._selectedSuggestion=null}).unbind("keyup.suggest").bind("keyup.suggest",function(){var $input=$(this),foundSuggestions=[],val=$.trim($input.val()).toLocaleLowerCase();if(val==$.formUtils._previousTypedVal){return}else{$.formUtils._previousTypedVal=val}var hasTypedSuggestion=false,suggestionId=$input.valAttr("suggestion-nr"),$suggestionContainer=$(".jquery-form-suggestion-"+suggestionId);$suggestionContainer.scrollTop(0);if(val!=""){var findPartial=val.length>2;$.each($input.data("suggestions"),function(i,suggestion){var lowerCaseVal=suggestion.toLocaleLowerCase();if(lowerCaseVal==val){foundSuggestions.push("<strong>"+suggestion+"</strong>");hasTypedSuggestion=true;return false}else if(lowerCaseVal.indexOf(val)===0||findPartial&&lowerCaseVal.indexOf(val)>-1){foundSuggestions.push(suggestion.replace(new RegExp(val,"gi"),"<strong>$&</strong>"))}})}if(hasTypedSuggestion||foundSuggestions.length==0&&$suggestionContainer.length>0){$suggestionContainer.hide()}else if(foundSuggestions.length>0&&$suggestionContainer.length==0){$suggestionContainer=$("<div></div>").css(conf.css).appendTo("body");$elem.addClass("suggestions-"+suggestionId);$suggestionContainer.attr("data-suggest-container",suggestionId).addClass("jquery-form-suggestions").addClass("jquery-form-suggestion-"+suggestionId)}else if(foundSuggestions.length>0&&!$suggestionContainer.is(":visible")){$suggestionContainer.show()}if(foundSuggestions.length>0&&val.length!=foundSuggestions[0].length){setSuggsetionPosition($suggestionContainer,$input);$suggestionContainer.html("");$.each(foundSuggestions,function(i,text){$("<div></div>").append(text).css({overflow:"hidden",textOverflow:"ellipsis",whiteSpace:"nowrap",padding:"5px"}).addClass("form-suggest-element").appendTo($suggestionContainer).click(function(){$input.focus();$input.val($(this).text());onSelectSuggestion($input)})})}}).unbind("keydown.validation").bind("keydown.validation",function(e){var code=e.keyCode?e.keyCode:e.which,suggestionId,$suggestionContainer,$input=$(this);if(code==13&&$.formUtils._selectedSuggestion!==null){suggestionId=$input.valAttr("suggestion-nr");$suggestionContainer=$(".jquery-form-suggestion-"+suggestionId);if($suggestionContainer.length>0){var newText=$suggestionContainer.find("div").eq($.formUtils._selectedSuggestion).text();$input.val(newText);onSelectSuggestion($input);e.preventDefault()}}else{suggestionId=$input.valAttr("suggestion-nr");$suggestionContainer=$(".jquery-form-suggestion-"+suggestionId);var $suggestions=$suggestionContainer.children();if($suggestions.length>0&&$.inArray(code,[38,40])>-1){if(code==38){if($.formUtils._selectedSuggestion===null)$.formUtils._selectedSuggestion=$suggestions.length-1;else $.formUtils._selectedSuggestion--;if($.formUtils._selectedSuggestion<0)$.formUtils._selectedSuggestion=$suggestions.length-1}else if(code==40){if($.formUtils._selectedSuggestion===null)$.formUtils._selectedSuggestion=0;else $.formUtils._selectedSuggestion++;if($.formUtils._selectedSuggestion>$suggestions.length-1)$.formUtils._selectedSuggestion=0}var containerInnerHeight=$suggestionContainer.innerHeight(),containerScrollTop=$suggestionContainer.scrollTop(),suggestionHeight=$suggestionContainer.children().eq(0).outerHeight(),activeSuggestionPosY=suggestionHeight*$.formUtils._selectedSuggestion;if(activeSuggestionPosY<containerScrollTop||activeSuggestionPosY>containerScrollTop+containerInnerHeight){$suggestionContainer.scrollTop(activeSuggestionPosY)}$suggestions.removeClass("active-suggestion").css("background","none").eq($.formUtils._selectedSuggestion).addClass("active-suggestion").css(conf.activeSuggestionCSS);e.preventDefault();return false}}}).unbind("blur.suggest").bind("blur.suggest",function(){onSelectSuggestion($(this))});return $elem},LANG:{errorTitle:"Form submission failed!",requiredFields:"You have not answered all required fields",badTime:"You have not given a correct time",badEmail:"You have not given a correct e-mail address",badTelephone:"You have not given a correct phone number",badSecurityAnswer:"You have not given a correct answer to the security question",badDate:"You have not given a correct date",lengthBadStart:"You must give an answer between ",lengthBadEnd:" characters",lengthTooLongStart:"You have given an answer longer than ",lengthTooShortStart:"You have given an answer shorter than ",notConfirmed:"Values could not be confirmed",badDomain:"Incorrect domain value",badUrl:"The answer you gave was not a correct URL",badCustomVal:"You gave an incorrect answer",badInt:"The answer you gave was not a correct number",badSecurityNumber:"Your social security number was incorrect",badUKVatAnswer:"Incorrect UK VAT Number",badStrength:"The password isn't strong enough",badNumberOfSelectedOptionsStart:"You have to choose at least ",badNumberOfSelectedOptionsEnd:" answers",badAlphaNumeric:"The answer you gave must contain only alphanumeric characters ",badAlphaNumericExtra:" and ",wrongFileSize:"The file you are trying to upload is too large",wrongFileType:"The file you are trying to upload is of wrong type",groupCheckedRangeStart:"Please choose between ",groupCheckedTooFewStart:"Please choose at least ",groupCheckedTooManyStart:"Please choose a maximum of ",groupCheckedEnd:" item(s)"}};$.formUtils.addValidator({name:"email",validatorFunction:function(email){var emailParts=email.toLowerCase().split("@");if(emailParts.length==2){return $.formUtils.validators.validate_domain.validatorFunction(emailParts[1])&&!/[^\w\+\.\-]/.test(emailParts[0])}return false},errorMessage:"",errorMessageKey:"badEmail"});$.formUtils.addValidator({name:"domain",validatorFunction:function(val,$input){var topDomains=[".ac",".ad",".ae",".aero",".af",".ag",".ai",".al",".am",".an",".ao",".aq",".ar",".arpa",".as",".asia",".at",".au",".aw",".ax",".az",".ba",".bb",".bd",".be",".bf",".bg",".bh",".bi",".bike",".biz",".bj",".bm",".bn",".bo",".br",".bs",".bt",".bv",".bw",".by",".bz",".ca",".camera",".cat",".cc",".cd",".cf",".cg",".ch",".ci",".ck",".cl",".clothing",".cm",".cn",".co",".com",".construction",".contractors",".coop",".cr",".cu",".cv",".cw",".cx",".cy",".cz",".de",".diamonds",".directory",".dj",".dk",".dm",".do",".dz",".ec",".edu",".ee",".eg",".enterprises",".equipment",".er",".es",".estate",".et",".eu",".fi",".fj",".fk",".fm",".fo",".fr",".ga",".gallery",".gb",".gd",".ge",".gf",".gg",".gh",".gi",".gl",".gm",".gn",".gov",".gp",".gq",".gr",".graphics",".gs",".gt",".gu",".guru",".gw",".gy",".hk",".hm",".hn",".holdings",".hr",".ht",".hu",".id",".ie",".il",".im",".in",".info",".int",".io",".iq",".ir",".is",".it",".je",".jm",".jo",".jobs",".jp",".ke",".kg",".kh",".ki",".kitchen",".km",".kn",".kp",".kr",".kw",".ky",".kz",".la",".land",".lb",".lc",".li",".lighting",".lk",".lr",".ls",".lt",".lu",".lv",".ly",".ma",".mc",".md",".me",".menu",".mg",".mh",".mil",".mk",".ml",".mm",".mn",".mo",".mobi",".mp",".mq",".mr",".ms",".mt",".mu",".museum",".mv",".mw",".mx",".my",".mz",".na",".name",".nc",".ne",".net",".nf",".ng",".ni",".nl",".no",".np",".nr",".nu",".nz",".om",".org",".pa",".pe",".pf",".pg",".ph",".photography",".pk",".pl",".plumbing",".pm",".pn",".post",".pr",".pro",".ps",".pt",".pw",".py",".qa",".re",".ro",".rs",".ru",".rw",".sa",".sb",".sc",".sd",".se",".sexy",".sg",".sh",".si",".singles",".sj",".sk",".sl",".sm",".sn",".so",".sr",".st",".su",".sv",".sx",".sy",".sz",".tattoo",".tc",".td",".technology",".tel",".tf",".tg",".th",".tips",".tj",".tk",".tl",".tm",".tn",".to",".today",".tp",".tr",".travel",".tt",".tv",".tw",".tz",".ua",".ug",".uk",".uno",".us",".uy",".uz",".va",".vc",".ve",".ventures",".vg",".vi",".vn",".voyage",".vu",".wf",".ws",".xn--3e0b707e",".xn--45brj9c",".xn--80ao21a",".xn--80asehdb",".xn--80aswg",".xn--90a3ac",".xn--clchc0ea0b2g2a9gcd",".xn--fiqs8s",".xn--fiqz9s",".xn--fpcrj9c3d",".xn--fzc2c9e2c",".xn--gecrj9c",".xn--h2brj9c",".xn--j1amh",".xn--j6w193g",".xn--kprw13d",".xn--kpry57d",".xn--l1acc",".xn--lgbbat1ad8j",".xn--mgb9awbf",".xn--mgba3a4f16a",".xn--mgbaam7a8h",".xn--mgbayh7gpa",".xn--mgbbh1a71e",".xn--mgbc0a9azcg",".xn--mgberp4a5d4ar",".xn--mgbx4cd0ab",".xn--ngbc5azd",".xn--o3cw4h",".xn--ogbpf8fl",".xn--p1ai",".xn--pgbs0dh",".xn--q9jyb4c",".xn--s9brj9c",".xn--unup4y",".xn--wgbh1c",".xn--wgbl6a",".xn--xkc2al3hye2a",".xn--xkc2dl3a5ee0h",".xn--yfro4i67o",".xn--ygbi2ammx",".xxx",".ye",".yt",".za",".zm",".zw"],ukTopDomains=["co","me","ac","gov","judiciary","ltd","mod","net","nhs","nic","org","parliament","plc","police","sch","bl","british-library","jet","nls"],dot=val.lastIndexOf("."),domain=val.substring(0,dot),ext=val.substring(dot,val.length),hasTopDomain=false;for(var i=0;i<topDomains.length;i++){if(topDomains[i]===ext){if(ext===".uk"){var domainParts=val.split(".");var tld2=domainParts[domainParts.length-2];for(var j=0;j<ukTopDomains.length;j++){if(ukTopDomains[j]===tld2){hasTopDomain=true;break}}if(hasTopDomain)break}else{hasTopDomain=true;break}}}if(!hasTopDomain){return false}else if(dot<2||dot>57){return false}else{var firstChar=domain.substring(0,1),lastChar=domain.substring(domain.length-1,domain.length);if(firstChar==="-"||firstChar==="."||lastChar==="-"||lastChar==="."){return false}if(domain.split(".").length>3||domain.split("..").length>1){return false}if(domain.replace(/[-\da-z\.]/g,"")!==""){return false}}if(typeof $input!=="undefined"){$input.val(val)}return true},errorMessage:"",errorMessageKey:"badDomain"});$.formUtils.addValidator({name:"required",validatorFunction:function(val,$el){return $el.attr("type")=="checkbox"?$el.is(":checked"):$.trim(val)!==""},errorMessage:"",errorMessageKey:"requiredFields"});$.formUtils.addValidator({name:"length",validatorFunction:function(val,$el,conf,lang){var lengthAllowed=$el.valAttr("length"),type=$el.attr("type");if(lengthAllowed==undefined){var elementType=$el.get(0).nodeName;alert('Please add attribute "data-validation-length" to '+elementType+" named "+$el.attr("name"));return true}var len=type=="file"&&$el.get(0).files!==undefined?$el.get(0).files.length:val.length,lengthCheckResults=$.formUtils.numericRangeCheck(len,lengthAllowed),checkResult;switch(lengthCheckResults[0]){case"out":this.errorMessage=lang.lengthBadStart+lengthAllowed+lang.lengthBadEnd;checkResult=false;break;case"min":this.errorMessage=lang.lengthTooShortStart+lengthCheckResults[1]+lang.lengthBadEnd;checkResult=false;break;case"max":this.errorMessage=lang.lengthTooLongStart+lengthCheckResults[1]+lang.lengthBadEnd;checkResult=false;break;default:checkResult=true}return checkResult},errorMessage:"",errorMessageKey:""});$.formUtils.addValidator({name:"url",validatorFunction:function(url){var urlFilter=/^(https?|ftp):\/\/((((\w|-|\.|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:)*@)?(((\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5]))|((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])(\w|-|\.|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])(\w|-|\.|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?)(:\d*)?)(\/(((\w|-|\.|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)+(\/((\w|-|\.|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)*)*)?)?(\?((([a-z]|\d|\[|\]|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|[\uE000-\uF8FF]|\/|\?)*)?(\#(((\w|-|\.|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|\/|\?)*)?$/i;if(urlFilter.test(url)){var domain=url.split("://")[1];var domainSlashPos=domain.indexOf("/");if(domainSlashPos>-1)domain=domain.substr(0,domainSlashPos);return $.formUtils.validators.validate_domain.validatorFunction(domain)}return false},errorMessage:"",errorMessageKey:"badUrl"});$.formUtils.addValidator({name:"number",validatorFunction:function(val,$el,conf){if(val!==""){var allowing=$el.valAttr("allowing")||"",decimalSeparator=$el.valAttr("decimal-separator")||conf.decimalSeparator,allowsRange=false,begin,end;if(allowing.indexOf("number")==-1)allowing+=",number";if(allowing.indexOf("negative")>-1&&val.indexOf("-")===0){val=val.substr(1)}if(allowing.indexOf("range")>-1){begin=parseFloat(allowing.substring(allowing.indexOf("[")+1,allowing.indexOf(";")));end=parseFloat(allowing.substring(allowing.indexOf(";")+1,allowing.indexOf("]")));allowsRange=true}if(decimalSeparator==","){val=val.replace(",",".")}if(allowing.indexOf("number")>-1&&val.replace(/[0-9]/g,"")===""&&(!allowsRange||val>=begin&&val<=end)){return true}if(allowing.indexOf("float")>-1&&val.match(new RegExp("^([0-9]+)\\.([0-9]+)$"))!==null&&(!allowsRange||val>=begin&&val<=end)){return true}}return false},errorMessage:"",errorMessageKey:"badInt"});$.formUtils.addValidator({name:"alphanumeric",validatorFunction:function(val,$el,conf,language){var patternStart="^([a-zA-Z0-9",patternEnd="]+)$",additionalChars=$el.attr("data-validation-allowing"),pattern="";if(additionalChars){pattern=patternStart+additionalChars+patternEnd;var extra=additionalChars.replace(/\\/g,"");if(extra.indexOf(" ")>-1){extra=extra.replace(" ","");extra+=" and spaces "}this.errorMessage=language.badAlphaNumeric+language.badAlphaNumericExtra+extra}else{pattern=patternStart+patternEnd;this.errorMessage=language.badAlphaNumeric}return new RegExp(pattern).test(val)},errorMessage:"",errorMessageKey:""});$.formUtils.addValidator({name:"custom",validatorFunction:function(val,$el,conf){var regexp=new RegExp($el.valAttr("regexp"));return regexp.test(val)},errorMessage:"",errorMessageKey:"badCustomVal"});$.formUtils.addValidator({name:"date",validatorFunction:function(date,$el,conf){var dateFormat="yyyy-mm-dd";if($el.valAttr("format")){dateFormat=$el.valAttr("format")}else if(conf.dateFormat){dateFormat=conf.dateFormat}return $.formUtils.parseDate(date,dateFormat)!==false},errorMessage:"",errorMessageKey:"badDate"});$.formUtils.addValidator({name:"checkbox_group",validatorFunction:function(val,$el,conf,lang,$form){var checkResult=true;var elname=$el.attr("name");var checkedCount=$("input[type=checkbox][name^='"+elname+"']:checked",$form).length;var qtyAllowed=$el.valAttr("qty");if(qtyAllowed==undefined){var elementType=$el.get(0).nodeName;alert('Attribute "data-validation-qty" is missing from '+elementType+" named "+$el.attr("name"))}var qtyCheckResults=$.formUtils.numericRangeCheck(checkedCount,qtyAllowed);switch(qtyCheckResults[0]){case"out":this.errorMessage=lang.groupCheckedRangeStart+qtyAllowed+lang.groupCheckedEnd;checkResult=false;break;case"min":this.errorMessage=lang.groupCheckedTooFewStart+qtyCheckResults[1]+lang.groupCheckedEnd;checkResult=false;break;case"max":this.errorMessage=lang.groupCheckedTooManyStart+qtyCheckResults[1]+lang.groupCheckedEnd;
    checkResult=false;break;default:checkResult=true}return checkResult}})})(jQuery);