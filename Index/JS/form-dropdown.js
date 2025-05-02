  const subcategories = {
    "Sécurité Publique": [
      "Agression",
      "Commerce illégal",
      "Vandalisme"
    ],
    "Propreté et environnement": [
      "Accumulation les déchets",
      "Absence de containners a ordures",
      "Prolifération des insectes et des rongeurs"
    ],
    "Gaz": [
      "Fuite de gaz",
      "Problème de pénurie",
      "Problème de compteur"
    ],
    "Routes et voirie": [
      "Nids de poules et des fissures",
      "Congestion du trafic",
      "Routes irrégulières"
    ],
    "Électricité": [
      "Coupures fréquentes d'électricité",
      "Éclairage insuffisant dans les rues",
      "Pannes des poteaux d'électricité"
    ],
    "Eau": [
      "Coupure d'eau",
      "Pollution d'eau",
      "Fuite d'eau"
    ]
  };

  const categorySelect = document.getElementById("category");
  const subcategorySelect = document.getElementById("subcategory");

  categorySelect.addEventListener("change", function () {
    const selectedCategory = this.value;
    subcategorySelect.innerHTML = '<option value="">-- Sélectionnez une sous-catégorie --</option>';

    if (subcategories[selectedCategory]) {
      subcategories[selectedCategory].forEach(sub => {
        const option = document.createElement("option");
        option.value = sub;
        option.textContent = sub;
        subcategorySelect.appendChild(option);
      });
    }
  });

// SCRIPT FOR GEOLOCATION 
    document.addEventListener('DOMContentLoaded', function() {
      const geoInput = document.querySelector('input[name="geolocation"]');
      
      geoInput.addEventListener('click', function() {
        if (navigator.geolocation) {
          navigator.geolocation.getCurrentPosition(
            (position) => {
              geoInput.value = `${position.coords.latitude}, ${position.coords.longitude}`;
            },
            (error) => {
              geoInput.value = "Could not get location";
              console.error("Geolocation error:", error);
            }
          );
        } else {
          geoInput.value = "Geolocation not supported";
        }
      });
    });

    // Function to display form errors from PHP session
document.addEventListener('DOMContentLoaded', function() {
  // Function to get URL parameters
  const getUrlParams = () => {
      const params = {};
      const queryString = window.location.search.substring(1);
      const pairs = queryString.split('&');
      
      for (let i = 0; i < pairs.length; i++) {
          const pair = pairs[i].split('=');
          params[decodeURIComponent(pair[0])] = decodeURIComponent(pair[1] || '');
      }
      
      return params;
  };

  // Check if there are any error parameters
  const params = getUrlParams();
  
  if (params.error) {
      // Create error message container if it doesn't exist
      let errorContainer = document.querySelector('.error-container');
      
      if (!errorContainer) {
          errorContainer = document.createElement('div');
          errorContainer.className = 'error-container';
          errorContainer.style.backgroundColor = '#ffdddd';
          errorContainer.style.color = '#ff0000';
          errorContainer.style.padding = '10px';
          errorContainer.style.marginBottom = '15px';
          errorContainer.style.borderRadius = '5px';
          
          // Insert error container at the top of the form
          const form = document.querySelector('form');
          form.insertBefore(errorContainer, form.firstChild);
      }
      
      // Display the error message
      switch(params.error) {
          case 'login':
              errorContainer.textContent = 'Invalid email or password. Please try again.';
              break;
          case 'signup':
              errorContainer.textContent = 'Error creating account. This email may already be registered.';
              break;
          case 'required':
              errorContainer.textContent = 'Please fill in all required fields.';
              break;
          case 'upload':
              errorContainer.textContent = 'There was a problem uploading your image. Please try again.';
              break;
          case 'report':
              errorContainer.textContent = 'There was a problem submitting your report. Please try again.';
              break;
          default:
              errorContainer.textContent = 'An error occurred. Please try again.';
      }
  }

  // Pre-fill form fields if available from session (for when there are errors)
  // This requires PHP to output the session data as JSON in the page
  if (typeof formData !== 'undefined' && formData) {
      // Loop through form data and fill inputs
      Object.keys(formData).forEach(field => {
          const input = document.querySelector(`[name="${field}"]`);
          if (input) {
              // Handle selects differently
              if (input.tagName === 'SELECT') {
                  for (let i = 0; i < input.options.length; i++) {
                      if (input.options[i].value === formData[field]) {
                          input.options[i].selected = true;
                          break;
                      }
                  }
              } else if (input.type === 'checkbox' || input.type === 'radio') {
                  input.checked = formData[field] === 'on' || formData[field] === '1' || formData[field] === 'true';
              } else {
                  input.value = formData[field];
              }
          }
      });
  }
});