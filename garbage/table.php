<table width="100%" border="0" cellspacing="0" cellpadding="0" class="outerTable">
	<tr>
		<td class="labelField" width="5%">�o�Ϳ����G</td>
		<td class="dataField" colspan="3">
			<select id="stationnumber" name="stationnumber" class="select2">
			</select>
		</td> 
	</tr>                                                
	<tr>
		<td class="labelField" width="5%">�򥢪����O�G</td>
		<td class="dataField" colspan="3">
			<select id="classcode" name="classcode" class="select2">
			</select>
		</td> 
	</tr>	
	
	<tr>
		<td class="labelField" width="5%">���D�m�W�G</td>
		<td class="dataField" width="25%" colspan="3">
			<input type="text" id= "applicant" name = "applicant" class="form-control" size="16" maxlength="30"/>
			<!--input id="foreigner" type="checkbox"/>�~�y�H�h</td-->
		</td>
	</tr>
	<tr>
		<td class="labelField"  width="5%">�����q�ܡG</td>
		<td class="dataField"  colspan="3" width="25%">
			<input type="text" id="tel" name="tel" class="form-control" size="16" maxlength="256"/>
		</td>                                 
	</tr> 
	<tr>
		<td class="labelField"  width="5%">��ʹq�ܡG</td>
		<td class="dataField"  colspan="3" width="25%">
			<input type="text" id="mobile" name="mobile" class="form-control" size="16" maxlength="256"/>
		</td>                                 
	</tr> 
	<tr>
		<td class="labelField"  width="5%">�q�l�l��G</td>
		<td class="dataField"  colspan="3" width="25%">
			<input type="text" id="remark" name="remark" class="form-control" size="40" maxlength="256"/>
		</td>                                 
	</tr>                                                
	<!--tr>
		<td class="labelField"   width="5%" >�ʧO�G</td>
		<td class="dataField"  width="25%"  colspan="3">                                                   
			<select  id="applicantgender" class="selectpicker" data-width="auto">
			</select>
		</td>                                                    
	</tr-->  
	<tr>
		<td class="labelField" width="5%">�򥢮ɶ��G</td>
		<td class="dataField" width="25%" colspan="3">
			<!--input type="text" id="lostdate" name="lostdate" class="form-control" size="8" maxlength="10" placeholder="��:104/02/23"/-->
			<input type="text" id="lostdate" name="lostdate" class="form-control"  size="5" onclick="WdatePicker({dateFmt:'yyy/MM/dd'})" onfocus="WdatePicker({dateFmt:'yyy/MM/dd'})" placeholder="104/02/23">&nbsp;
			<img onclick="WdatePicker({el:'lostdate',dateFmt:'yyy/MM/dd'})" onfocus="WdatePicker({el:'lostdate',dateFmt:'yyy/MM/dd'})"
			src="assets/img/calendar.gif" align="absmiddle" alt="��ܤ��" style="cursor:pointer" />                                                        
			<!--input type="text" id="losttime" name = "losttime" class="form-control" size="4" maxlength="5" placeholder="��:09:30"/-->
			<input type="text" class="form-control" id="losttime"  size="2" onclick="WdatePicker({dateFmt:'HH:mm'})" onfocus="WdatePicker({dateFmt:'HH:mm'})" placeholder="09:30">&nbsp;
			<img onclick="WdatePicker({el:'losttime',dateFmt:'HH:mm'})" onfocus="WdatePicker({el:'losttime',dateFmt:'HH:mm'})"
			src="assets/img/calendar.gif" align="absmiddle" alt="��ܮɶ�" style="cursor:pointer" />                                                        
		</td>  
	</tr>
	<tr >
		<td class="labelField" width="5%" rowspan="2">�򥢦a�I�G</td>
		<td class="dataField"  colspan="3">
			<textarea id="lostplace" name="lostplace" class="form-control  ui-widget-content" rows="3" style="width:50%" maxlength="256"  ></textarea>
		</td>
			
	</tr>                                                
	<tr><!--td  class="labelField" width="5%"></td-->
		
		<td class="dataField"  colspan="3">�ж�g�W���a�I�ΤU���a�I<br /></td></tr-->

	<tr>
		<td class="labelField" width="5%">�򥢪����e�G</td>
		<td class="dataField" colspan="3">
			<textarea id="mainarticle" name="mainarticle" class="form-control  ui-widget-content"  rows="3" style="width:50%" maxlength="256" ></textarea>
		</td> 
	</tr>
	<tr>
		<td width="5%"  class="labelField" >�򥢪����e��g�G</td>
		<td class="dataField" colspan="3">1.�~�P�B�����B�C��ίS�x�C<br />2.�p���h�˿򥢪��ɡA�п�ܨ�L���~�C</td>
	</tr>                                                
	<tr>
		<td width="5%"  class="labelField" >���ҽX�G</td>
		<td>
			<input type="text" id="answer" name="answer" class="form-control" size="10" maxlength="10" title="�п�J���ҽX" onclick="javascript:document.all.answer.value = '';" />
			<img src="./stickyImg" id="reload-img"/><img src="assets/images/120px-System-software-update.png" id="reload-captcha" onclick="resetCaptcha()" alt="reload" width="40" height="40"/>
			&nbsp;<input type="text" id="showCpt" name="showCpt" class="form-control" size="5" maxlength="10" disabled="true"/>&nbsp;<button id="btnShowCaptcha" type="button" class="btn blue btn-sm" onclick="getCaptcha()">&nbsp;�L��ê���U</button>
		</td>
	</tr>   
	<tr>
		<td colspan="4" align="center">
			<button id="btnSave" type="button" class="btn blue btn-sm" ><i class="fa fa-check"></i>&nbsp;�e�X</button><!--onClick="doAddAction()"-->
		</td>                                                
	</tr>
	<tr>
		<td colspan="4" style="color:red;"><p>���t�ξA��</p><p>Google Chrome 39�ΥH�᪺����</p><p>Microsoft Internet Explorer 8�ΥH�᪺����</p></td>
	</tr>                                                
</table>