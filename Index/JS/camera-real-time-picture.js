document.addEventListener('DOMContentLoaded', function() {
    const video = document.getElementById('cameraPreview');
    const canvas = document.getElementById('photoCanvas');
    const photoPreview = document.getElementById('photoPreview');
    const startCameraBtn = document.getElementById('startCamera');
    const takePhotoBtn = document.getElementById('takePhoto');
    const retakePhotoBtn = document.getElementById('retakePhoto');
    const fileInput = document.getElementById('fileInput');
    const form = document.getElementById('reportForm');
    const geolocationInput = document.getElementById('geolocation');
    
    let stream = null;

    // Start camera
    startCameraBtn.addEventListener('click', async function() {
      try {
        stream = await navigator.mediaDevices.getUserMedia({ 
          video: { 
            facingMode: 'environment', 
            width: { ideal: 1280 },
            height: { ideal: 720 }
          }, 
          audio: false 
        });
        video.srcObject = stream;
        video.style.display = 'block';
        startCameraBtn.style.display = 'none';
        takePhotoBtn.style.display = 'inline-block';
      } catch (err) {
        console.error("Camera error: ", err);
        alert("Could not access the camera. Please check permissions or use file upload instead.");
        fileInput.style.display = 'block';
      }
    });

    // Take photo
    takePhotoBtn.addEventListener('click', function() {
      const context = canvas.getContext('2d');
      canvas.width = video.videoWidth;
      canvas.height = video.videoHeight;
      context.drawImage(video, 0, 0, canvas.width, canvas.height);
      
      // Stop camera stream
      if (stream) {
        stream.getTracks().forEach(track => track.stop());
      }
      
      // Show the captured photo
      const imageDataUrl = canvas.toDataURL('image/jpeg', 0.8);
      photoPreview.src = imageDataUrl;
      photoPreview.style.display = 'block';
      
      // Hide video and show retake button
      video.style.display = 'none';
      takePhotoBtn.style.display = 'none';
      retakePhotoBtn.style.display = 'inline-block';
    });

    // Retake photo
    retakePhotoBtn.addEventListener('click', function() {
      photoPreview.style.display = 'none';
      retakePhotoBtn.style.display = 'none';
      startCameraBtn.style.display = 'inline-block';
    });

    // Get geolocation if user clicks the input
    geolocationInput.addEventListener('click', function() {
      if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
          function(position) {
            geolocationInput.value = `${position.coords.latitude}, ${position.coords.longitude}`;
          },
          function(error) {
            console.error("Geolocation error: ", error);
            geolocationInput.value = "Could not get location";
          }
        );
      } else {
        geolocationInput.value = "Geolocation not supported";
      }
    });

    // Form submission - fallback to traditional submission if JavaScript fails
    form.addEventListener('submit', function(e) {
      // If camera was used, we need to handle the submission manually
      if (photoPreview.style.display !== 'none') {
        e.preventDefault();
        
        // Convert canvas to blob and create a File object
        canvas.toBlob(function(blob) {
          const file = new File([blob], 'report_photo.jpg', { type: 'image/jpeg' });
          
          // Create a new FormData and append all form fields
          const formData = new FormData(form);
          
          // Remove any existing file input
          if (fileInput.files.length > 0) {
            formData.delete('report_image');
          }
          
          // Add our captured photo
          formData.append('report_image', file);
          
          // Submit via fetch API
          fetch(form.action, {
            method: 'POST',
            body: formData
          })
          .then(response => {
            if (response.redirected) {
              window.location.href = response.url;
            } else {
              return response.text().then(text => {
                window.location.href = 'form-filled.html';
              });
            }
          })
          .catch(err => {
            console.error('Error:', err);
            // Fallback to traditional form submission
            form.submit();
          });
        }, 'image/jpeg', 0.8);
      }
      // If no photo was taken but file was uploaded, let it submit normally
    });
  });