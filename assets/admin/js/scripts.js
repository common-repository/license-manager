jQuery(document).ready(function($)
	{

        $('.license-manager-date').datepicker({
            dateFormat : 'yy-mm-dd'
        	});



		$(document).on('click', '.remove-domain', function()
			{
                index = $(this).attr('index');

                alert("Please confirm");

                $(this).parent().remove();

			})











	});	







