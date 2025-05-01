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