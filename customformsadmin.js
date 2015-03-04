function handleFields()
{
	this.type = document.getElementById("field_type").value;
	this.isText = this.type == "text" || this.type == "textarea";
	this.regexMask = this.type == "text" || this.type == "textarea";
	this.regexFmt = this.type == "select";
	this.dimension = this.type == "textarea";
	this.size = this.type == "select";
	this.bbc = this.type == "text" || this.type == "textarea" || this.type == "select" || this.type == "radio" || this.type == "check";
	this.opts = this.type == "select" || this.type == "radio";
	this.def = this.type == "check";
}

function updateInputBoxes(b)
{
	var hf = new handleFields();
	document.getElementById("max_length_dt").style.visibility = hf.isText ? 'visible' : 'hidden';
	document.getElementById("max_length_dd").style.visibility = hf.isText ? 'visible' : 'hidden';
	document.getElementById("dimension_dt").style.visibility = hf.dimension ? 'visible' : 'hidden';
	document.getElementById("dimension_dd").style.visibility = hf.dimension ? 'visible' : 'hidden';
	document.getElementById("size_dt").style.visibility = hf.size ? 'visible' : 'hidden';
	document.getElementById("size_dd").style.visibility = hf.size ? 'visible' : 'hidden';
	document.getElementById("bbc_dt").style.visibility = hf.bbc ? 'visible' : 'hidden';
	document.getElementById("bbc_dd").style.visibility = hf.bbc ? 'visible' : 'hidden';
	document.getElementById("options_dt").style.visibility = hf.opts ? 'visible' : 'hidden';
	document.getElementById("options_dd").style.visibility = hf.opts ? 'visible' : 'hidden';
	document.getElementById("default_dt").style.visibility = hf.def ? 'visible' : 'hidden';
	document.getElementById("default_dd").style.visibility = hf.def ? 'visible' : 'hidden';
	document.getElementById("mask_dt").style.visibility = hf.regexMask ? 'visible' : 'hidden';
	document.getElementById("mask_dd").style.visibility = hf.regexMask ? 'visible' : 'hidden';
}

function updateInputBoxes2(b)
{
	regexMask = document.getElementById("field_mask").value == 'regex';
	document.getElementById("regex_dt").style.visibility = regexMask ? 'visible' : 'hidden';
	document.getElementById("regex_dd").style.visibility = regexMask ? 'visible' : 'hidden';
}

function addOption()
{
	setOuterHTML(document.getElementById("addopt"), '<br><input type="radio" name="default_select" value="' + startOptID + '" id="' + startOptID + '"><input type="text" name="select_option[' + startOptID + ']" value="">');
	startOptID++;
}