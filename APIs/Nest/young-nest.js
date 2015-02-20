/* globals $, Firebase */
'use strict';

var nestToken  = $.cookie('nest_token'),
    structure  = {},
    thermostats, smokealarms;


if (nestToken) { // Simple check for token
    // Create a reference to the API using the provided token
    var dataRef = new Firebase('wss://developer-api.nest.com');
    dataRef.authWithCustomToken(nestToken, function(error, authData) {
        if (error) {
            console.log("Login Failed!", error);
        } else {
            console.log("Login Succeeded!", authData);
        }
    });
} else {
    // No auth token, go get one
    console.log('no token');
    if(document.location.host.indexOf('localhost')>-1){
        window.location.href='nesttest.php';
    }else{
        window.location.href='index.php';
    }


}

/**
 The appropriate version of target temperature to display is based on
 the following parameters:

 * hvac_mode (C or F)
 * temperature_scale (range, heat, cool, or off)

 When hvac_mode is 'range' we display both the low and the high setpoints like:

 68 • 80° F

 For 'heat' or 'cool' just the temperature is displayed

 70° F

 For 'off' we show that the thermostat is off:

 OFF

 Away modes are handled separately

 @method
 @param object thermostat model
 @returns undefined
 */
function updateTemperatureDisplay (thermostat) {
    var scale = thermostat.temperature_scale.toLowerCase();

    // For Heat • Cool mode, we display a range of temperatures
    // we support displaying but not changing temps in this mode
    if (thermostat.hvac_mode === 'range') {
        $('#target-temperature .temp').text(
            thermostat['target_temperature_low_' + scale] + ' • ' +
            thermostat['target_temperature_high_' + scale]
        );

        // Display the string 'off' when the thermostat is turned off
    } else if (thermostat.hvac_mode === 'off') {
        $('#target-temperature .temp').text('off');

        // Otherwise just display the target temperature
    } else {
        $('#target-temperature .temp').text(thermostat['target_temperature_' + scale] + '°');
    }

    // Update ambient temperature display
    $('#ambient-temperature .temp').text(thermostat['ambient_temperature_' + scale] + '°');
}

/**
 Updates the thermostat view with the latests data

 * Temperature scale
 * HVAC mode
 * Target and ambient temperatures
 * Device name

 @method
 @param object thermostat model
 @returns undefined
 */
function updateThermostatView(thermostat) {
    var scale = thermostat.temperature_scale;

    $('.temperature-scale').text(scale);
    $('#target-temperature .hvac-mode').text(thermostat.hvac_mode);
    $('#device-name').text(thermostat.name);
    updateTemperatureDisplay(thermostat);
}

/**
 Updates the structure's home/away state by
 adding the class 'home' when the structure is
 set to home, and removing it when in any away state

 @method
 @param object structure
 @returns undefined
 */
function updateStructureView (structure) {
    if (structure.away === 'home') {
        $('#target-temperature').addClass('home');
    } else {
        $('#target-temperature').removeClass('home');
    }
}

/**
 Updates the thermostat's target temperature
 by the specified number of degrees in the
 specified scale. If a type is specified, it
 will be used to set just that target temperature
 type

 @method
 @param Number degrees
 @param String temperature scale
 @param String type, high or low. Used in heat-cool mode (optional)
 @returns undefined
 */
function adjustTemperature(degrees, scale, type) {
    scale = scale.toLowerCase();
    type = type ? type + '_' : '';
    var newTemp = thermostat['target_temperature_' + scale] + degrees,
        path = 'devices/thermostats/' + thermostat.device_id + '/target_temperature_' + type + scale;

    if (thermostat.is_using_emergency_heat) {
        console.error("Can't adjust target temperature while using emergency heat.");
    } else if (thermostat.hvac_mode === 'heat-cool' && !type) {
        console.error("Can't adjust target temperature while in Heat • Cool mode, use target_temperature_high/low instead.");
    } else if (type && thermostat.hvac_mode !== 'heat-cool') {
        console.error("Can't adjust target temperature " + type + " while in " + thermostat.hvac_mode +  " mode, use target_temperature instead.");
    } else if (structure.away.indexOf('away') > -1) {
        console.error("Can't adjust target temperature while structure is set to Away or Auto-away.");
    } else { // ok to set target temperature
        dataRef.child(path).set(newTemp);
    }
}

/**
 When the user clicks the up button,
 adjust the temperature up 1 degree F
 or 0.5 degrees C

 */
$('#up-button').on('click', function () {
    var scale = thermostat.temperature_scale,
        adjustment = scale === 'F' ? +1 : +0.5;
    adjustTemperature(adjustment, scale);
});

/**
 When the user clicks the down button,
 adjust the temperature down 1 degree F
 or 0.5 degrees C

 */
$('#down-button').on('click', function () {
    var scale = thermostat.temperature_scale,
        adjustment = scale === 'F' ? -1 : -0.5;
    adjustTemperature(adjustment, scale);
});

/**
 When the user clicks the heating up button,
 adjust the temperature up 1 degree F
 or 0.5 degrees C

 */
$('#up-button-heat').on('click', function () {
    var scale = thermostat.temperature_scale,
        adjustment = scale === 'F' ? +1 : +0.5;
    adjustTemperature(adjustment, scale, 'heat');
});

/**
 When the user clicks the heating down button,
 adjust the temperature down 1 degree F
 or 0.5 degrees C

 */
$('#down-button-heat').on('click', function () {
    var scale = thermostat.temperature_scale,
        adjustment = scale === 'F' ? -1 : -0.5;
    adjustTemperature(adjustment, scale, 'heat');
});

/**
 When the user clicks the cooling up button,
 adjust the temperature up 1 degree F
 or 0.5 degrees C

 */
$('#up-button-cool').on('click', function () {
    var scale = thermostat.temperature_scale,
        adjustment = scale === 'F' ? +1 : +0.5;
    adjustTemperature(adjustment, scale, 'cool');
});

/**
 When the user clicks the cooling down button,
 adjust the temperature down 1 degree F
 or 0.5 degrees C

 */
$('#down-button-cool').on('click', function () {
    var scale = thermostat.temperature_scale,
        adjustment = scale === 'F' ? -1 : -0.5;
    adjustTemperature(adjustment, scale, 'cool');
});

/**
 Utility method to return the first child
 value of the passed in object.

 @method
 @param object
 @returns object
 */
function firstChild(object) {
    for(var key in object) {
        return object[key];
    }
}


/* extra funtion for testing. */

function printObject(obj) {
    var out = '';
    for (var p in obj) {
        if(typeof obj[p] =='object'){
            out += p +' : <br /> '+ printObject(obj[p]);
        }else{
            out += ' ---- '+p + ': ' + obj[p] + '<br />';
        }
    }
    return out;
}
/*
    update temperature setting on device with device id
*/
function updateTemperature(device_id, updown) {

    var type = type ? type + '_' : '';
    var thermo = thermostats[device_id];
    var scale = thermo.temperature_scale.toLowerCase();
    var path = 'devices/thermostats/' + device_id + '/target_temperature_' + type + scale;

    var degrees = ( updown === 'up' ) ? +1 : -1;
    var newTemp = thermo['target_temperature_' + scale] + degrees;
    console.log(thermo);
    console.log(path+newTemp);
    if (thermo.is_using_emergency_heat) {
        console.error("Can't adjust target temperature while using emergency heat.");
    } else if (thermo.hvac_mode === 'heat-cool' && !type) {
        console.error("Can't adjust target temperature while in Heat • Cool mode, use target_temperature_high/low instead.");
    } else if (type && thermo.hvac_mode !== 'heat-cool') {
        console.error("Can't adjust target temperature " + type + " while in " + thermo.hvac_mode +  " mode, use target_temperature instead.");
    } else if (structure.away.indexOf('away') > -1) {
        console.error("Can't adjust target temperature while structure is set to Away or Auto-away.");
    } else {    // ok to set target temperature
        dataRef.child(path).set(newTemp);
    }

}

function updateThermostat (thermostat) {
    var scale = thermostat.temperature_scale.toLowerCase();
    var device_id = thermostat.device_id;
    // For Heat • Cool mode, we display a range of temperatures
    // we support displaying but not changing temps in this mode
    if (thermostat.hvac_mode === 'range') {
        $('#'+device_id+'target_temperature .temp').text(
            thermostat['target_temperature_low_' + scale] + ' • ' +
            thermostat['target_temperature_high_' + scale]
        );

        // Display the string 'off' when the thermostat is turned off
    } else if (thermostat.hvac_mode === 'off') {
        $('#'+device_id+'_target_temperature .temp').text('off');

        // Otherwise just display the target temperature
    } else {
        $('#'+device_id+'_target_temperature .temp').text(thermostat['target_temperature_' + scale] + '°');
        console.log(thermostat['target_temperature_' + scale] );
    }
    $('#'+device_id+'_target_temperature .hvac-mode').text(thermostat.hvac_mode);
    // Update ambient temperature display
    $('#'+device_id+'_ambient_temperature .temp').text(thermostat['ambient_temperature_' + scale] + '°');
    $('#'+device_id+'_ambient_temperature .temperature-scale').text(scale.toLocaleUpperCase());

}

/* create thermo view */
function createThermo(thermoObj){
    var device_id = thermoObj.device_id;
    var newdiv = $('<div class="thermostat"><div class="screen">' +
        '<div class="target_temperature" id="'+device_id+'_target_temperature"><div > <span class="temp"></span><div class="hvac-mode"></div></div></div>' +
        '<div class="ambient_temperature" id="'+device_id+'_ambient_temperature"> <span class="temp"></span>    <span class="temperature-scale"></span>  <span class="label">inside</span>    </div>'+
        '<div class="door">   <div class="device-name" id="'+device_id+'_device_name">'+ thermoObj.name+' </div>  ' +
        '<div class="buttons"> ' +
        '<button class="up-button" onclick="updateTemperature(\''+device_id+'\', \'up\')">⬆</button> <span > Set Temperature </span><button class="down-button" onclick="updateTemperature(\''+device_id+'\', \'down\')">⬇︎</button>  </div> ' +
        '</div>'+
        '<div></div>');
    newdiv.attr('id', device_id);
    $('.thermostats').append(newdiv);
}
function changeAlarm(device_id, what, newstatus){
    var path = 'devices/smoke_co_alarms/' + device_id + '/'+what;
console.log(path);
    dataRef.child(path).set(newstatus);


}
function updateAlarm(alarmObj){
    var device_id = alarmObj.device_id;
    $('#'+device_id).css('background-color', alarmObj.ui_color_state);
    var statuscolor;
    switch(alarmObj.co_alarm_state){
        case 'warning':
            statuscolor = 'orange';
            break;
        case 'emergency':
            statuscolor = 'red';
            break;
        default :
            statuscolor = 'green';
    }
    $('#'+device_id+' .coscreen').css('background-color', statuscolor);
    switch(alarmObj.smoke_alarm_state){
        case 'warning':
            statuscolor = 'orange';
            break;
        case 'emergency':
            statuscolor = 'red';
            break;
        default :
            statuscolor = 'green';
    }
    $('#'+device_id+' .smokescreen').css('background-color', statuscolor);
    switch(alarmObj.battery_health){
        case 'ok':
            statuscolor = 'green';
            break;
        case 'replace':
            statuscolor = 'red';
            break;
        default :
            statuscolor = 'green';
    }
    $('#'+device_id+' .batteryscreen').css('background-color', statuscolor);
}
/* create alarm view */
function createAlarm(alarmObj){
    var device_id = alarmObj.device_id;
    var newdiv = $('<div class="smokealarm '+alarmObj.ui_color_state+'" style="background-color:'+alarmObj.ui_color_state+';">' +
    '<div class="coscreen '+alarmObj.co_alarm_state+'"> CO Alarm</div>' +
    '<div class="smokescreen '+alarmObj.smoke_alarm_state+'"> Smoke Alarm </div>' +
    '<div class="batteryscreen '+alarmObj.battery_health+'"> Battery Health </div>' +
    '<div class="device-name" id="'+device_id+'_device_name"> '+ alarmObj.name_long+' </div>  ' +
    '</div>'+
    '<div class="manual"> Manual Manipulation [ not available now ] <br />' +
    '<div class="buttons"> ' +
    '<span > change Alarm state</span><button class="botton" onclick="changeAlarm(\''+device_id+'\', \'co_alarm_state\',\'ok\')">OK</button> <button class="button" onclick="changeAlarm(\''+device_id+'\', \'co_alarm_state\', 0)">Warning︎</button>  </div> ' +
    '</div>' +
    '</div>');
    newdiv.attr('id', device_id);
    $('.smokealarms').append(newdiv);
}
/**
 */
dataRef.on('value', function (snapshot) {
    var data = snapshot.val();
    console.log(data);
    window.data = data;
    var dataText = 'devices <br />' + printObject(data.devices)+'<br /> metadata <br />' + printObject(data.metadata)+'<br /> structures <br />' + printObject(data.structures);
    $('#fortesting').html(dataText);

    structure = firstChild(data.structures);
    updateStructureView(structure);

    // Devices
    thermostats = data.devices.thermostats;
    smokealarms = data.devices.smoke_co_alarms;
    var curthermo, curalarm, device_id;

    for(var thermo in thermostats){
        curthermo = thermostats[thermo];
        device_id = curthermo.device_id;
        if($('#'+device_id+'_target_temperature').length == 0) {
            createThermo(curthermo);
        }
        updateThermostat(curthermo);
    }

    for(var alarm in smokealarms){
        curalarm = smokealarms[alarm];
        device_id = curalarm.device_id;
        if($('#'+device_id).length == 0) {
            createAlarm(curalarm);
        }
        updateAlarm(curalarm);
    }
});


