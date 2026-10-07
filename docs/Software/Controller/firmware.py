#!/usr/bin/env python

import RPi.GPIO as GPIO
from mfrc522 import SimpleMFRC522

import requests, json, socket
import time, board, neopixel
import urllib3
from w1thermsensor import W1ThermSensor
sensor = W1ThermSensor()

WEBSERVICE_ENDPOINT = "https://192.168.111.45/php/authorization/authorization_rooms.php"
HTTP_REQUEST_TIMEOUT_SECONDS = 5    # number of seconds before interrupting http request

RGB_LED_PIN = board.D18             # data in pin of first rgb led
RGB_NUM_LEDS = 2                    # number of rgb leds chained together
RGB_ORDER = neopixel.RGB            # pin ordering of rgb leds

SOLENOID_PIN = 5                    # pin where the solenoid lock is connected to
SOLENOID_TIMEOUT = 5                # time in seconds that the lock stays open

EMERGENCY_BUTTON_PIN = 17           # pin where the emergency button is connected to

BUZZER_PIN = 6                      # buzzer pin
BUZZER_TIMEOUT = 0.05               # time in seconds between two beeps

# this function uses the beeper to emit a number of short beeps
def beep( nbrOfBeeps ):
    for i in range(0, nbrOfBeeps):
        # enable buzzer
        GPIO.output(BUZZER_PIN, GPIO.HIGH)
        time.sleep(BUZZER_TIMEOUT)

        # disable buzzer
        GPIO.output(BUZZER_PIN, GPIO.LOW)
        time.sleep(BUZZER_TIMEOUT)

# this function displays a color on the first rgb led
def showColor( color ):
    pixels[0] = color
    pixels.show()

# this function displays a color on the first rgb led
def showColor2( color ):
    pixels[1] = color
    pixels.show()

# this function opens the solenoid lock
def openLock():
    GPIO.output(SOLENOID_PIN, GPIO.HIGH)

# this function closes the solenoid lock
def closeLock():
    GPIO.output(SOLENOID_PIN, GPIO.LOW)

# this function sends a GET request to the webservice endpoint with optional http parameters
def getJSON( httpParameters={} ):
    payload = httpParameters
    print("sending request to ", WEBSERVICE_ENDPOINT, " with rfid=\'", httpParameters['rfid'], "\' and hostname=\'", httpPara>
    urllib3.disable_warnings(urllib3.exceptions.InsecureRequestWarning)
    response = requests.get(WEBSERVICE_ENDPOINT, params=payload, timeout=HTTP_REQUEST_TIMEOUT_SECONDS, verify=False)
    data = response.json()
    return data


# this function returns whether the rfid batch passed as parameter can open the lock
def canRfidOpenLock( rfid ):
    #prepare http parameters with rfid and hostname
    payload = {'rfid': rfid, 'hostname': socket.gethostname()}

    # send http request to the webservice endpoint
    data = getJSON(payload)

    # check whether json response contains the name "allowed" and see if its value is true
    if ("allowed" in data and data['allowed']):
        return True
    else:
        return False

# this function defines the routine when the emergency button has been pressed
def emergencyButtonRoutine( channel ):
    print("Emergency button pressed!")
    permissionGrantedRoutine()

# this function defines the routine when an exception occurs such as:
# webservice not reachable, http timeout, problem parsing json data ...
def runExceptionRoutine():
    print("Exception happened!")
    for i in range(0, 3):
        showColor((255, 0, 0)) # show green on first rgb led
        beep(1)
        time.sleep(0.5)
        showColor((0, 0, 0))  # turn first rgb off
        time.sleep(0.5)

# this function defines the routine when the firmware is launched
def runStartupRoutine():

    closeLock()
    for i in range(0, 5):
        showColor((0, 255, 0)) # show green on first rgb led
        time.sleep(0.5)
        showColor((0, 0, 0))  # turn first rgb off
        time.sleep(0.5)

# this function defines the routine when permission has been granted to open the lock
def permissionGrantedRoutine():
    beep(1)
    showColor((0, 255, 0))
    print("door access granted")
    openLock()
    time.sleep(SOLENOID_TIMEOUT)
    closeLock()
    showColor((0, 0, 0))

# this function defines the routine when permission has been denied to open the lock
def permissionDeniedRoutine():
    print("door access denied")
    showColor((255, 0, 0))
    beep(2)
    time.sleep(2)
    showColor((0, 0, 0,))


# use BCM numbering (GPIO numbers instead of hardware pins)
GPIO.setmode(GPIO.BCM)

# activate the RFID reader
reader = SimpleMFRC522()

# activate the RGB leds
pixels = neopixel.NeoPixel(RGB_LED_PIN, RGB_NUM_LEDS, brightness=0.1, auto_write=False, pixel_order=RGB_ORDER)

# setup the solenoid pin as output pin
GPIO.setup(SOLENOID_PIN, GPIO.OUT)

# setup the buzzer pin as output pin
GPIO.setup(BUZZER_PIN, GPIO.OUT)

# setup the emergency button pin as input pin with pulldown resistor
GPIO.setup(EMERGENCY_BUTTON_PIN, GPIO.IN, pull_up_down=GPIO.PUD_DOWN)

# define interrupt when the button is pressed with a bouncing time of 100msecs
GPIO.add_event_detect(EMERGENCY_BUTTON_PIN, GPIO.RISING, callback=emergencyButtonRoutine, bouncetime=100)

try:
    runStartupRoutine()
    # set the initial last_temp_check_time to the current time
    last_temp_check_time = time.time()
    while True:
        #id, text = reader.read() # try to read an rfid batch. this is blocking!

        # we use the non-blocking read so we can still react on the temperature values
        id, text = reader.read_no_block() # try to read an rfid batch. this is not blocking!

        # check if we could read an rfid batch
        if (id != None):
            # HACK ALERT: NeoPixel code when executed at boot crashes when user logs in
            # Source: https://www.reddit.com/r/raspberry_pi/comments/rj03vk/systemd_service_python_script_stops_after_ssh/.co>
            # Solution is to recreate NeoPixel object
            pixels.deinit()
            pixels = neopixel.NeoPixel(RGB_LED_PIN, RGB_NUM_LEDS, brightness=0.1, auto_write=False, pixel_order=RGB_ORDER)

        # check if 5 minutes have passed since the last temperature check
        if time.time() - last_temp_check_time >= 300:
            # get the temperature reading
            temperature = sensor.get_temperature()

            # print the temperature reading
            print("Temperature: ", temperature)

            # update the last_temp_check_time to the current time
            last_temp_check_time = time.time()

            # check if temperature is higher than 25 degrees Celsius
            if temperature > 25:
                # change the LED color to red
                showColor2((255, 0, 0))
            else:
                # change the LED color to blue
                showColor2((0, 0, 255))

            timestamp = time.strftime ('%Y-%m-%d %H:%M:%S')
            print("RFID detected: ", id, " at: ", timestamp)
            showColor((0, 0, 255)) # show blue on first rgb led
            time.sleep(1)
            try:
                answer = canRfidOpenLock(id) # ask webservice if door can be unlocked
                if (answer):
                    permissionGrantedRoutine() # open lock
                else:
                    permissionDeniedRoutine() # keep lock closed

            except requests.exceptions.RequestException as err: # HTTP exceptions
                print("Connection error: ", str(err))
                runExceptionRoutine()
            except ValueError as err: # JSON parsing exceptions
                print("Error parsing json: ", str(err))
                runExceptionRoutine()

        time.sleep(0.5) # sleep to reduce cpu load
except:
    GPIO.cleanup()
    raise
