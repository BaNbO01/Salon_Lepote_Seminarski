import React from "react";
import Button from "./Button";

const ServiceCard = ({
  service,
  onAction,
  actionLabel = "",
  currency = "RSD",
  rate = 1,
}) => {
  const izracunataCena = service.cena_raw * rate;

  const formatiranaCena =
    currency === "RSD"
      ? Math.round(izracunataCena).toLocaleString()
      : izracunataCena.toLocaleString(undefined, {
          minimumFractionDigits: 2,
          maximumFractionDigits: 2,
        });

  return (
    <div className="bg-white rounded-3xl p-6 shadow-lg hover:shadow-xl transition-all duration-300 border border-gray-50 flex flex-col justify-between group h-full">
      <div>
        <div className="flex justify-between items-start mb-4">
          <span className="px-3 py-1 bg-pink-50 text-pink-700 text-[10px] font-bold uppercase tracking-widest rounded-full">
            {service.kategorija}
          </span>
          <span className="text-gray-400 text-xs flex items-center gap-1">
            🕒 {service.trajanje}
          </span>
        </div>
        <h3 className="text-xl font-bold text-gray-800 mb-2 group-hover:text-pink-800 transition-colors">
          {service.naziv}
        </h3>
        <p className="text-gray-500 text-sm leading-relaxed mb-6">
          {service.opis}
        </p>
      </div>

      <div className="flex items-center justify-between pt-4 border-t border-gray-50">
        <div className="flex flex-col">
          <span className="text-[10px] font-black uppercase text-gray-400 tracking-tighter">
            Cena u {currency}
          </span>
          <span className="text-xl font-black text-gray-900">
            {formatiranaCena}{" "}
            <span className="text-sm font-bold text-pink-700">{currency}</span>
          </span>
        </div>

        {actionLabel && (
          <Button size="sm" onClick={onAction} className="!rounded-xl">
            {actionLabel}
          </Button>
        )}
      </div>
    </div>
  );
};

export default ServiceCard;
