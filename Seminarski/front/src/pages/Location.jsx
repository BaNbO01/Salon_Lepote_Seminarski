import React from "react";
import SalonMap from "../components/SalonMap";

const Location = () => {
  return (
    <div className="min-h-screen bg-white">
      <main className="max-w-7xl mx-auto">
        <div className="p-8 md:p-10 flex flex-col lg:flex-row lg:items-center justify-between gap-8">
          <div className="space-y-1">
            <h1 className="text-4xl font-serif text-pink-900 tracking-tight">
              Gde se nalazimo
            </h1>
            <p className="text-gray-400 font-medium italic">
              Posetite nas u srcu Beograda i prepustite se našim stručnjacima
            </p>
          </div>

          <div className="hidden lg:block">
            <div className="flex gap-4">
              <div className="text-right border-r-2 border-pink-100 pr-4">
                <p className="text-[10px] font-black uppercase tracking-widest text-pink-900">
                  Telefon
                </p>
                <p className="text-gray-700 font-serif">+381 64 123 456</p>
              </div>
              <div className="text-right">
                <p className="text-[10px] font-black uppercase tracking-widest text-pink-900">
                  Adresa
                </p>
                <p className="text-gray-700 font-serif">
                  Jove Ilića 154, Beograd
                </p>
              </div>
            </div>
          </div>
        </div>

        <div className="px-8 md:px-10 pb-16">
          <div className="relative group">
            <SalonMap />
          </div>
        </div>
      </main>
    </div>
  );
};

export default Location;
