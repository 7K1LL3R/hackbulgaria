<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Query over CSV</title>
        <link href="css/style.css" rel="stylesheet" type="text/css" />
        <script src="js/jquery-1.11.2.min.js"></script>
    </head>
    
    <body>
        <div class="content">
            <h3>Query language over a CSV file</h3>
            
            <form action="" method="post">
                <div class="block form-field">
                    <label class="form-label">Query: </label>
                    
                    <textarea id="query" class="form-textarea" name="query" rows="4" cols="50"></textarea>
                </div>
                
                <div class="block form-field">
                    <div id="query-execute" class="form-submit">
                        Execute
                    </div>
                </div>
            </form>
            
            <div class="block">
                <div class="help">
                    <p>
                        Supported queries:
                    </p>
                    
                    <ul>
                        <li>SELECT [columns] LIMIT X</li>
                        
                        <li>SUM [column]</li>
                        
                        <li>SHOW</li>
                        
                        <li>FIND X</li>
                    </ul>
                </div>
            </div>
            
            <div class="block">
                <div id="errors" class="errors">
                    
                </div>
            </div>
            
            <div class="block">
                <div id="data">
                    
                </div>
            </div>
        </div>
    </body>
</html>

<script type="text/javascript">
    $(document).ready(function() {
        $('#query-execute').on('click', function() {
            $.ajax({
                url: "ProcessQuery.php", 
                cache: false, 
                type: "POST", 
                data: {query : $.trim($('#query').val())}, 
                dataType: "json"
            }).done(function(data) {
                if(data.errors.length !== 0)
                {
                    var errorMessages = '<p>Error messages: </p><ul>';
                    
                    for(var i = 0; i < data.errors.length; i++)
                    {
                        errorMessages += '<li>' + data.errors[i] + '</li>';
                    }
                    
                    errorMessages += '</ul>';
                    
                    $('#errors').html(errorMessages).slideDown(400, 'linear');
                    
                    $('#data').html('');
                }
                else
                {
                    $('#errors').slideUp(200, 'linear').html('');
                    
                    $('#data').html(data.html);
                }
            });
        });
    });
</script>