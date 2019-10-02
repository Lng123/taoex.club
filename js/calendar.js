var calendar = { 
	dayTable:null,
	year:null,
	month:null,

	getFirstDay:function(year,month){
		"use strict";
		var firstDay = new Date(year,month,1); 
		return firstDay.getDay();
	}, 
	getMonthLen:function(year,month){
		"use strict";
		var nextMonth = new Date(year,month+1,1);
		nextMonth.setHours(nextMonth.getHours() - 3);
		return nextMonth.getDate();
	}, 
	createCalendar:function(form,date){
		"use strict";
		calendar.year = date.getFullYear();
		calendar.month = date.getMonth();
		form.getElementsByTagName('th')[1].innerHTML = calendar.year + ' - ' + (calendar.month + 1);
		calendar.clearCalendar(form); //清空TABLE 
		var monthLen = calendar.getMonthLen(calendar.year,calendar.month); //获取月份长度 
		var firstDay = calendar.getFirstDay(calendar.year,calendar.month);
		for(var i = 1;i <= monthLen;i++){
			calendar.dayTable[i+firstDay-1].innerHTML = i;
			if((i+firstDay-2) === new Date().getDate() && calendar.month === new Date().getMonth() && calendar.year === new Date().getFullYear()){	calendar.dayTable[i+firstDay-1].id = 'today'; 
			}
		}
	}, 
	clearCalendar:function(form){
		"use strict";
		this.dayTable = form.getElementsByTagName('td'); 
		for(var i = 0;i < this.dayTable.length;i++){ 
			this.dayTable[i].innerHTML = ' '; 
			this.dayTable[i].id = ''; 
		}
	}, 
	init:function(form){
		"use strict";
		this.dayTable = form.getElementsByTagName('td'); 
		this.createCalendar(form,new Date()); 
		var preMon = form.getElementsByTagName('th')[0]; 
		var nextMon = form.getElementsByTagName('th')[2]; 
		preMon.onclick = function(){
			calendar.createCalendar(form,new Date(calendar.year,calendar.month-1,1)); 
		};
		nextMon.onclick = function(){	calendar.createCalendar(form,new Date(calendar.year,calendar.month+1,1)); 
		};
	}
}; 
window.onload = function(){
	"use strict";
	var calendars = document.getElementById('calendar'); 

	calendar.init(calendars); 
}; 
