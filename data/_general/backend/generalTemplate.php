<?php
    function input1($inputLength,$inputType,$labelText,$id,$required,$icon,$value="",$readonly="",$class="",$extra=""){
        return '
            <div class="form-group has-feedback has-feedback-left form-group-material '.$inputLength.'">
                <label class="control-label multi_lang">'.$labelText.'</label>
                <input type="'.$inputType.'" id="'.$id.'" name="'.$id.'" value="'.$value.'" class="form-control multi_lang '.$class.'"  '.$extra.' placeholder="'.$labelText.'" '.$required.'  '.$readonly.'>
                <div class="form-control-feedback">
                    <i class="'.$icon.' text-muted"></i>
                </div>
            </div>
        ';
    }
    function input2($inputLength,$values,$labelText,$id,$required,$icon,$keyorValue=0,$class="",$extra=""){
        $option="";
		foreach($values as $key=>$value){
            $option.='<option class="multi_lang" value="'.($keyorValue==1?$value:$key).'">'.($keyorValue==1?$key:$value).'</option>';
        }
        return '
            <div class="form-group has-feedback has-feedback-left form-group-material '.$inputLength.'">
                <label class="control-label multi_lang">'.$labelText.'</label>
                <select class="form-control multi_lang select '.$class.'" data-width="100%" data-info=\''.$extra.'\' name="'.$id.'" id="'.$id.'" placeholder="'.$labelText.'" required="'.$required.'">
                    '.$option.'
				</select>
                <div class="form-control-feedback">
                    <i class="'.$icon.' text-muted"></i>
                </div>
            </div>
        ';
    }
    function input4($inputLength,$values,$labelText,$id,$required,$icon,$keyorValue=0,$class="",$extra=""){
        $option="";
		foreach($values as $key=>$value){
            $option.='<option class="multi_lang" value="'.($keyorValue==1?$value:$key).'" '.$extra.'>'.($keyorValue==1?$key:$value).'</option>';
        }
        return '
            <div class="'.$inputLength.'">
				<div class="input-group" style="padding-top:27px;">
					<span class="input-group-addon"><i class="'.$icon.'"></i></span>
					<div class="multi-select-full">
						<select class=" multi_lang '.$class.'" multiple="multiple" id="'.$id.'" name="'.$id.'[]" placeholder="'.$labelText.'" required="'.$required.'">
							'.$option.'
						</select>
					</div>
				</div>
			</div>
        ';
    }
    function input3($inputLength,$labelText,$id,$class){
        return '
            <div class="'.$inputLength.'">
                <label class="control-label multi_lang">'.$labelText.'</label><br/>
                <textarea class="'.$class.'" name="'.$id.'" id="'.$id.'"></textarea>
            </div>
        ';
    }
    function button1($id,$type,$text,$icon,$class="btn btn-primary btn-labeled btn-labeled-right",$extra=""){
        return '
            <div class="form-group">
                <button type="'.$type.'" class="'.$class.'" id="'.$id.'" '.$extra.'>
                    <span class="multi_lang">'.$text.'</span> 
                    <b><i class="'.$icon.'"></i></b>
                </button>
            </div>
        ';
    }
    function button2($id,$type,$text,$icon,$class="btn btn-primary btn-labeled btn-labeled-right",$extra=''){
        return '
            <button type="'.$type.'" class="'.$class.'" id="'.$id.'" '.$extra.'>
                <span class="multi_lang">'.$text.'</span> 
                <b><i class="'.$icon.'"></i></b>
            </button>
        ';
    }
    function button3($id,$link,$text,$icon,$class,$extra=''){
        return '
            <a href="'.$link.'" id="'.$id.'" class="'.$class.'" '.$extra.' >
                <span class="multi_lang">'.$text.'</span> 
                <b><i class="'.$icon.'"></i></b>
            </a>
        ';
    }
    function file1($inputLength,$id,$buttonText,$required=''){
        $splitID=explode("_",$id);
        $imgID=$splitID[0]."_".$splitID[1][0]."FR".$splitID[1][3];
        return '
            <div class="'.$inputLength.'" id="container-'.$id.'">
                <div id="imgContainer-'.$id.'" class="imgContainer">
                    <img src="#" title="" alt="" id="'.$imgID.'" >
                </div>
                <div class="input-group">
                    <div tabindex="500" class="form-control">
                        <div title="">
                            <i class="glyphicon glyphicon-file kv-caption-icon"></i>
                            <span id="name-'.$id.'"></span>
                        </div>
                    </div>
                    <div class="input-group-btn">
                        <button type="button" tabindex="500" title="Remove Image" id="remove-'.$id.'" class="btn btn-default removeFile">
                            <i class="icon-cross3"></i> 
                            <span class="hidden-xs">Remove</span>
                        </button>
                        <div tabindex="500" class="btn btn-primary btn-file">
                            <i class="icon-file-plus"></i>
                            <span class="hidden-xs">'.$buttonText.'</span>
                            <input class="" id="'.$id.'" name="'.$id.'" type="file" accept="image/*" onchange="readURL(this)" '.$required.'>
                        </div>
                    </div>
                </div>
            </div>
        ';
    }
    function file3($inputLength,$id,$buttonText,$required=''){
        return '
            <div class="'.$inputLength.'" id="container-'.$id.'">
                <div class="input-group">
                    <div tabindex="500" class="form-control">
                        <div title="">
                            <i class="glyphicon glyphicon-file kv-caption-icon"></i>
                            <span id="name-'.$id.'">Please Add CSV File... </span>
                        </div>
                    </div>
                    <div class="input-group-btn">
                        <div tabindex="500" class="btn btn-primary btn-file">
                            <i class="icon-file-plus"></i>
                            <span class="hidden-xs">'.$buttonText.'</span>
                            <input class="" id="'.$id.'" name="'.$id.'" type="file" accept="csv" '.$required.'>
                        </div>
                    </div>
                </div>
            </div>
        ';
    }
    function file2($inputLength,$id,$src="#",$required=''){
        $splitID=explode("_",$id);
        $imgID=$splitID[0]."_".$splitID[1][0]."FR".$splitID[1][3];
        return '
            <div class="'.$inputLength.'">
                <span class="btn btn-default btn-file" style="box-shadow: 0 0 3px rgba(0, 0, 0, 0); background-color: rgba(245, 245, 245, 0);">
                    <a href="#" id="profileLink" class="profile-thumb">
                        <img src="'.$src.'" class="img-circle img-xl" alt="" id="'.$imgID.'" width="350px" height="350px">
                        <input class="" id="'.$id.'" name="'.$id.'" type="file" accept="image/*" onchange="readURL_V2(this)" '.$required.'>
                    </a>
                </span>
            </div>
        ';
    }
?>