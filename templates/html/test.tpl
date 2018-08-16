<style type="text/css">
	.boolean{
	overflow:hidden;
	}
	.boolean label{
	float:left;
	padding:0.5em;
	line-height:38px;
	}
	.boolean .style{
	float:left;
	background:gray;
	border-radius:20px;
	height:35px;
	padding:2px;
	margin:10px;
	width:70px;
	position:relative;
	}
	.boolean input{
	position:absolute;
	left:-5000px;
	top:-5000px;
	}
	.boolean .style div{
	background:white;
	border-radius:50%;
	height:31px;
	width:31px;
	float:right;
	}
	.boolean.on .style{
	background:blue;
	}
	.boolean.on .style div{
	float:left;
	}
</style>
<script type="text/javascript">
	$(document).on( 'click', '.boolean label', function(){
		var bOn				=	$('input#' + $(this).attr('for') ).val();
		if( bOn == 1 ){
			$(this).parents('.boolean').addClass( 'on' );
		} else {
			$(this).parents('.boolean').removeClass( 'on' );
		}
	});
	$(document).on( 'style', '.boolean label', function(){
		$(this).parents('.boolean').find('input:not-check').click();
	});
</script>
<div class="boolean">
	<input type="radio" name="online" id="publie" value="1" />
	<label for="publie">Publié</label>
	<div class="style">
		<div></div>
	</div>
	<label for="brouillon">Brouillon</label>
	<input type="radio" name="online" id="brouillon" value="0" />
</div>
