


   $(document).ready(function()
{    

    $(".button-collapse").sideNav();

    $(".dropdown-button").dropdown();

    /*$('.datepicker').pickadate({
    selectMonths: true, // Creates a dropdown to control month
    selectYears: 5, // Creates a dropdown of 15 years to control year
    format: 'dd/mm/yyyy',
    monthNames: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'],
  });*/

    $(".datepicker").datepicker(
    	{
	      monthNames: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'],
	      dayNamesMin: [ "Di", "Lu", "Ma", "Me", "Je", "Ve", "Sa" ],
	      dateFormat: "yy-mm-dd"
       	}
       	);


    
       

});


$(window).scroll(
               function() 
       { console.log("Hello"); });


