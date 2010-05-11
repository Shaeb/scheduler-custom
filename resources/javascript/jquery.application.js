// application js for iScheduler

jQuery.createVisualQueue = function(){ 
	// setup the block
	var div = document.createElement('div');
	div.setAttribute('class', 'visualQueue');
	
	// setup the calendar picture
	var dateDiv = document.createElement('div');
	dateDiv.setAttribute('class', 'dateBlock');
	
	var monthDiv = document.createElement('div');
	monthDiv.setAttribute('class', 'dateMonth');
	monthDiv.innerHTML = 'April';
	dateDiv.appendChild(monthDiv);
	
	var dayDiv = document.createElement('div');
	dayDiv.setAttribute('class', 'dateDay');
	dayDiv.innerHTML = '23rd';
	dateDiv.appendChild(dayDiv);
	
	div.appendChild(dateDiv);
	div.innerHTML += 'test';
	
	// now add it to the document
	var body = document.getElementsByTagName('body')[0];
	body.appendChild(div);
	
	delete div;
	delete body;
};