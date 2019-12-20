<div id="search_msg"></div>
<div id="search_form">
<div id="add_criteria"><i class="fas fa-plus"></i> Add criteria</div>
<form action="" method="post" id="account_search" class="form-inline" onsubmit="return false;">
</form>

<div id="search_accounts"><i class="fas fa-search"></i> Search accounts</div>
</div>
<div id="search_result"></div>
<!-- Begin hidden fields -->
<div style="display: none">
	<select name="field" id="account_field" class="field protected form-control">
	<option value="acct_value">Account value</option> 
	<option value="acct_status_desc">Account status</option> 
	<option value="agent_name">Agent name</option> 
	<option value="agent_id">Agent ID</option> 
	<option value="ga_num">Bank name</option> 	
	<option value="city">City</option> 
	<option value="dob">Date of birth</option> 
	<option value="iss_date">Date of issue</option> 
	<option value="init_deposit">Initial deposit</option> 
	<option value="name">Name</option> 
	<option value="policy">Policy number</option> 
	<option value="field_nq">Qualified status</option>
	<option value="field_r">RMD status</option>
	<option value="state">State</option> 
	<option value="street1">Street address</option> 
	<option value="zip">Zip code</option> 
	</select>
	<select name="operator" id="account_operator" class="form-control operator protected">
	<option rel="eq" value="=">equals</option>
	<option rel="like" value="LIKE">similar to</option>
	<option rel="between" value="BETWEEN">is between</option>
	<option rel="gt" value=">">greater than</option>
	<option rel="lt" value="<">less than</option>
	<option rel= "neq" value="<>">not equal to</option>
	</select>
	<select id="field_r" class="form-control lookup">
	<option value="Y">Yes</option>
	<option value="N">No</option>
	</select>
	<select id="field_nq" class="form-control lookup">
	<option value="Q">Qualified</option>
	<option value="N">Nonqualified</option>
	</select>
	<select id="acct_status_desc" class="form-control lookup">
	<option value="IN FORCE">In Force</option>
	<option value="DEATH CLAIM">Death Claim</option>
	<option value="SURRENDERED(1035)">Surrendered (1035)</option>
	<option value="SURRENDERED (Other)">Surrendered (Other)</option>
	<option value="CANCELLATION">Cancellation</option>
	</select>	
	<select id="ga_num" class="form-control lookup">
	<option value="A06154">BancMutual Financial</option>
	<option value="82198">BancMutual Financial & Insurance Services</option>
	<option value="95934">BB&T Investments</option>
	<option value="A12175">BB&T Investment Services, Inc.</option>
	<option value="26385">Colonial Investment Services, Inc.</option>
	<option value="80784">First Citizens Investor Services, Inc.</option>
	<option value="A01701">First Citizens Securities</option>
	<option value="A06151">First Citizens Corp</option>	
	<option value="01464">FirstTrust Financial Resources</option>
	<option value="95613">First Merit Insurance Agency</option>
	<option value="15433">Infinex Insurance Agency</option>
	<option value="14584">Infinex Investments, Inc.</option>
	<option value="23444">IRNB Investment Services</option>
	<option value="00305">Key Investment Services LLC</option>
	<option value="A09592">Mo-Kan Insurance Services, Inc. (A09592)</option>
	<option value="A34987">Mo-Kan Insurance Services, Inc. (A34987)</option>	
	<option value="A22207">New Alliance Bank</option>
	<option value="A06967">New Alliance Investments</option>
	<option value="65000">Parkvale Savings Bank</option>
	<option value="A10396">RBC Capital Markets</option>
	<option value="A17275">Rick Lebel</option>
	</select>	
</div>
<!-- End hidden fields -->