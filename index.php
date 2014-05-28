<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Untitled Document</title>
</head>

  <link rel="stylesheet" href="//code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css">
  <script src="//code.jquery.com/jquery-1.10.2.js"></script>
  <script src="//code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
  <link rel="stylesheet" href="/resources/demos/style.css">
 <style>
  .custom-combobox {
    position: relative;
    display: inline-block;
  }
  .custom-combobox-toggle {
    position: absolute;
    top: 0;
    bottom: 0;
    margin-left: -1px;
    padding: 0;
    /* support: IE7 */
    *height: 1.7em;
    *top: 0.1em;
  }
  .custom-combobox-input {
    margin: 0;
    padding: 0.2 em;
  }
  </style>
  
<script>
$( document ).ready(function() {
	//alert( "ready!" );
    });

(function( $ ) {
  $.widget( "custom.combobox", {
    _create: function() {
      this.wrapper = $( "<span>" )
        .addClass( "custom-combobox" )
        .insertAfter( this.element );

      this.element.hide();
      this._createAutocomplete();
      this._createShowAllButton();
    },

    _createAutocomplete: function() {
      var selected = this.element.children( ":selected" ),
        value = selected.val() ? selected.text() : "";

      this.input = $( "<input>" )
        .appendTo( this.wrapper )
        .val( value )
        .attr( "title", "" )
        .addClass( "custom-combobox-input ui-widget ui-widget-content ui-state-default ui-corner-left" )
        .autocomplete({
          delay: 0,
          minLength: 0,
          source: $.proxy( this, "_source" )
        })
        .tooltip({
          tooltipClass: "ui-state-highlight"
        });

      this._on( this.input, {
        autocompleteselect: function( event, ui ) {
          ui.item.option.selected = true;
          this._trigger( "select", event, {
            item: ui.item.option
          });
        },

        autocompletechange: "_removeIfInvalid"
      });
    },

    _createShowAllButton: function() {
      var input = this.input,
        wasOpen = false;

      $( "<a>" )
        .attr( "tabIndex", -1 )
        .attr( "title", "Show All Items" )
        .tooltip()
        .appendTo( this.wrapper )
        .button({
          icons: {
            primary: "ui-icon-triangle-1-s"
          },
          text: false
        })
        .removeClass( "ui-corner-all" )
        .addClass( "custom-combobox-toggle ui-corner-right" )
        .mousedown(function() {
          wasOpen = input.autocomplete( "widget" ).is( ":visible" );
        })
        .click(function() {
          input.focus();

          // Close if already visible
          if ( wasOpen ) {
            return;
          }

          // Pass empty string as value to search for, displaying all results
          input.autocomplete( "search", "" );
        });
    },

    _source: function( request, response ) {
      var matcher = new RegExp( $.ui.autocomplete.escapeRegex(request.term), "i" );
      response( this.element.children( "option" ).map(function() {
        var text = $( this ).text();
        if ( this.value && ( !request.term || matcher.test(text) ) )
          return {
            label: text,
            value: text,
            option: this
          };
      }) );
    },

    _removeIfInvalid: function( event, ui ) {

      // Selected an item, nothing to do
      if ( ui.item ) {
        return;
      }

      // Search for a match (case-insensitive)
      var value = this.input.val(),
        valueLowerCase = value.toLowerCase(),
        valid = false;
      this.element.children( "option" ).each(function() {
        if ( $( this ).text().toLowerCase() === valueLowerCase ) {
          this.selected = valid = true;
          return false;
        }
      });

      // Found a match, nothing to do
      if ( valid ) {
        return;
      }

      // Remove invalid value
      this.input
        .val( "" )
        .attr( "title", value + " didn't match any item" )
        .tooltip( "open" );
      this.element.val( "" );
      this._delay(function() {
        this.input.tooltip( "close" ).attr( "title", "" );
      }, 2500 );
      this.input.data( "ui-autocomplete" ).term = "";
    },

    _destroy: function() {
      this.wrapper.remove();
      this.element.show();
    }
  });
})( jQuery );
<?php 
require_once('util/config.php');
// print '1';
$link = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD);
//   print '2';
if (!$link) {
	die('Failed to connect to server: ' . mysql_error());
}

//Select database
$db = mysql_select_db(DB_DATABASE);
if (!$db) {
	die("Unable to select database");
}
?>
//load Suburb
var data="<option value=''></option>";
<?php
$query = "select * from suburb";
$result = mysql_query($query);
while ($suburb = mysql_fetch_array($result)) {
	print 'data += \'<option value="'.$suburb['suburb'].'">'.$suburb['suburb'].'</option>\';';
	//"<option value='Gaga'>Gaga</option>"+
	//"<option value='Richmond'>Richmond</option>"+
	//"<option value='Glenroy'>Glenroy</option>";
	}
?>

//load postcode
var dataPostcode="<option value=''></option>";
<?php
$query = "select * from postcode";
$result = mysql_query($query);
while ($suburb = mysql_fetch_array($result)) {
	print 'dataPostcode += \'<option value="'.$suburb['postcode'].'">'.$suburb['postcode'].'</option>\';';
	//"<option value='Gaga'>Gaga</option>"+
	//"<option value='Richmond'>Richmond</option>"+
	//"<option value='Glenroy'>Glenroy</option>";
	}
?>
</script>
<body>

<?php

    $query = "SELECT t.id, s.suburb, c.name, c.mobile, c.email, p.postcode FROM coding.takecare t
				LEFT JOIN contact c on c.id=t.contact_id
				LEFT JOIN suburb s on s.id=t.suburb_id
				LEFT JOIN postcode p on p.id=t.postcode_id" or die(mysql_error());   
    $result = mysql_query($query); 
    print '<table style="border: 1px">';
    print '<th>Name</th><th>Email</th><th>Mobile</th><th>Suburb</th><th>Postcode</th><tbody>';
	while ($office = mysql_fetch_array($result)) { 
		print '<tr>';
		print '<td>'.$office['name'].'</td>';
        print '<td>'.$office['email'].'</td>'; 
        print '<td>'.$office['mobile'].'</td>';
        //print '<td>'.$office['suburb'].'</td>';
        print '<td style="width:255px;"><select id=cb_'.$office['id'].'></select>
      		<script>
        		$(function() { 
        		var gaga =data;
        		if(data.indexOf(\''.$office['suburb'].'\') != -1) {
      				var position = data.indexOf(\''.$office['suburb'].'\') + (\''.$office['suburb'].'\').length +1;
      		    	gaga = data.substr(0, position) + " selected" + data.substr(position);
      			}
      			$( "#cb_'.$office['id'].'" ).append(gaga);
    			$( "#cb_'.$office['id'].'" ).combobox();});
      		</script></td>';
        
        //print '<td>'.$office['postcode'].'</td>';
        print '<td style="width:255px;"><select id=cbPostcode_'.$office['id'].'></select>
      		<script>
        		$(function() {
        		var gaga = dataPostcode;
        		if(dataPostcode.indexOf(\''.$office['postcode'].'\') != -1) {
      				var position = dataPostcode.indexOf(\''.$office['postcode'].'\') + (\''.$office['postcode'].'\').length +1;
      		    	gaga = dataPostcode.substr(0, position) + " selected" + dataPostcode.substr(position);
      			}
      			$( "#cbPostcode_'.$office['id'].'" ).append(gaga);
    			$( "#cbPostcode_'.$office['id'].'" ).combobox();});
      		</script></td>';
        print '</tr>';
    } 
    print '</tbody></table>';
?>
</body>
</html>
