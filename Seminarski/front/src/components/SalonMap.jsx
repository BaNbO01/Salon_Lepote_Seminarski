import React from "react";
import { MapContainer, TileLayer, Marker, Popup } from "react-leaflet";
import "leaflet/dist/leaflet.css";
import L from "leaflet";

import markerIcon from "leaflet/dist/images/marker-icon.png";
import markerShadow from "leaflet/dist/images/marker-shadow.png";

let DefaultIcon = L.icon({
  iconUrl: markerIcon,
  shadowUrl: markerShadow,
  iconSize: [25, 41],
  iconAnchor: [12, 41],
});
L.Marker.prototype.options.icon = DefaultIcon;

const SalonMap = () => {
  const salonPosition = [44.772841542180714, 20.4752554630766];

  const openNavigation = () => {
    const [lat, lng] = salonPosition;
    window.open(`https://www.google.com/maps?q=${lat},${lng}`, "_blank");
  };

  return (
    <div className="rounded-[2rem] overflow-hidden shadow-2xl border-4 border-white relative">
      <MapContainer
        center={salonPosition}
        zoom={16}
        scrollWheelZoom={false}
        className="h-[400px] w-full z-0"
      >
        <TileLayer
          attribution="© OpenStreetMap contributors"
          url="https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png"
        />
        <Marker position={salonPosition}>
          <Popup>
            <div className="text-center p-2">
              <p className="font-bold text-pink-600 mb-1">✨ Salon Lepote</p>
              <button
                onClick={openNavigation}
                className="text-[10px] uppercase tracking-widest bg-pink-500 text-white px-3 py-2 rounded-lg hover:bg-pink-600 transition"
              >
                Kako stići
              </button>
            </div>
          </Popup>
        </Marker>
      </MapContainer>
    </div>
  );
};

export default SalonMap;
