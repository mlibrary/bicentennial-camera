// ** Capture a still image **
// This block of code is contained in a self invoked function.

(function() {
    // Set a width and height for the video/image

    // $("#video-still").width($("#video-still").width());

    var initialized = false;

    var width = 320; // We will scale the photo width to this
    var height = 0; // This will be computed based on the input stream

    // |streaming| indicates whether or not we're currently streaming
    // video from the camera. Obviously, we start at false.

    var streaming = false;

    // The various HTML elements we need to configure or control. These
    // will be set by the startup() function.

    var video = null;
    var canvas = null;
    var photo = null;
    var startbutton = null;
    var savebutton = null;
    var retakebutton = null;
    var $frame;
    var $slider;

    var position = null;

    navigator.geolocation.getCurrentPosition(function(result) {
      position = result;
      // do_something(position.coords.latitude, position.coords.longitude);
    });

    // ****************************************************************
    // Start capturing video
    function startup() {
        // Define elements
        video = document.getElementById('video-still');
        canvas = document.getElementById('canvas');
        photo = document.getElementById('photo');
        startbutton = document.getElementById('startbutton');
        savebutton = document.getElementById('save-photo');
        retakebutton = document.getElementById('retake-photo');
        overlay = document.getElementById('overlay-photo');
        $frame = $(".take-photo-view")
        $slider = $("#adjust-opacity");



        width = $(video).width();
        height = $(overlay).height();
        $(overlay).parent().height(height);
        // height = video.clientHeight;
        // width = height / ( 3 / 4);

        // Check for vendor version of getUserMedia

        $slider.rangeslider({ polyfill: false }).val($(video).css('opacity') * 100.0).change();


        $slider.on('change', function() {
            var opacity = $(this).val() / 100.0;
            $(video).css({ opacity: opacity });
        })


        navigator.getMedia = (navigator.getUserMedia ||
            navigator.webkitGetUserMedia ||
            navigator.mozGetUserMedia ||
            navigator.msGetUserMedia);

        // Check for getUserMedia
        if (!navigator.getMedia) {
            // No user media exit
            console.log("Has get user media");
            return;
        }

        // invoke getUserMedia to start a video stream.
        navigator.getMedia({
            video: true,    // Get video
            audio: false    // No audio
        }, getMediaSuccess, getMediaError); // Pass a success, and error function

        // Media success function
        function getMediaSuccess(stream) {
            // Check for FireFox.
            if (navigator.mozGetUserMedia) {
                video.mozSrcObject = stream; // Assign the stream to #video-still
            } else {
                var vendorURL = window.URL || window.webkitURL;
                video.src = vendorURL.createObjectURL(stream); // Assign the stream to #video-still
            }
            video.play(); // Tell #video-still to play
        }

        // This is invoked on a user media error.
        function getMediaError(error) {
            console.log("An error occured! " + error);
        }

        // Add an event to the #video-still. The canplay event occurs when
        // the video is ready to play.
        video.addEventListener('canplay', function (event) {
            // Check the streaming flag.
            if (!streaming) {
                // Not streaming.
                // Get the height of the video
                ratio = 
                height = video.videoHeight / (video.videoWidth / width);

                // Firefox currently has a bug where the height can't be read from
                // the video, so we will make assumptions if this happens.

                if (isNaN(height)) {
                    height = width / (4 / 3);
                }

                // Set some attributes of the #video-still element
                video.setAttribute('width', width);
                video.setAttribute('height', height);

                // canvas.setAttribute('width', width);
                // canvas.setAttribute('height', height);
                var ratio = 2048 / video.videoWidth;
                canvas.setAttribute('width', 2048);
                canvas.setAttribute('height', video.videoHeight * ratio);

                // Set streaming to true
                streaming = true;
            }
        }, false);

        // Add click event to #startbutton
        startbutton.addEventListener('click', function (event) {
            event.preventDefault();
            takepicture(); // Take a picture
        }, false);

        savebutton.addEventListener('click', function (event) {
            event.preventDefault();
            var dataURL = canvas.toDataURL();
            var description = $("textarea[name=description]").val();
            $.ajax({
                type: 'POST',
                url: location.pathname.replace('/camera/', '/upload/'),
                data: {
                    imgBase64: dataURL,
                    loc_long: position.coords.longitude,
                    loc_lat: position.coords.latitude,
                    description: description
                }
            }).done(function(o) {
                console.log('SAVED', o);
                window.location.href = o;
            });
        }, false);

        retakebutton.addEventListener('click', function(event) {
            event.preventDefault();
            clearphoto();
        })

        clearphoto();
    }
    // End startup function
    // ****************************************************************


    // #canvas is used to hold a still image. Here the convas is cleared
    // by filling with gray.
    function clearphoto() {
        // Fill with a gray
        var context = canvas.getContext('2d');
        context.fillStyle = "#AAA";
        context.fillRect(0, 0, canvas.width, canvas.height);
        // Set the data url of #photo to the image on the canvas
        var data = canvas.toDataURL('image/jpg');
        photo.setAttribute('src', data);
        if ( initialized ) {
            $(".toolbar").toggleClass('hidden');
            $(photo).toggleClass('invisible');
            $(video).toggleClass('invisible');
            $(overlay).toggleClass('hidden');
            $frame.height($(overlay).height()).css('min-height', '');
        } else {
            initialized  = true;
        }
    }

    // Capture a photo by fetching the current contents of the video
    // and drawing it into a canvas, then converting that to a PNG
    // format data URL. By drawing it on an offscreen canvas and then
    // drawing that to the screen, we can change its size and/or apply
    // other changes before drawing it.

    // Capture an image.

    function takepicture() {
        var context = canvas.getContext('2d');
        if (width && height) {
            // canvas.width = width;
            // canvas.height = height;
            context.drawImage(video, 0, 0, canvas.width, canvas.height);

            var data = canvas.toDataURL('image/jpg');
            photo.setAttribute('src', data);
            $(photo).toggleClass('invisible');
            $(video).toggleClass('invisible');
            $(overlay).toggleClass('hidden');
            setTimeout(function() {
                $frame.height($(photo).height()).css('min-height', 0);
                $(".toolbar").toggleClass("hidden");
            })
        } else {
            clearphoto();
        }
    }


    // Set up our event listener to run the startup process
    // once loading is complete.
    // This calls startup (above)
    window.addEventListener('load', startup, false);
})();
