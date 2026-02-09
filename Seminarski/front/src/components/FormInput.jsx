import React from "react";

const FormInput = ({
  label,
  type = "text",
  name,
  value,
  onChange,
  placeholder,
  required = false,
  className = "",
  accentColor = "pink",
}) => {
  const starColors = {
    pink: "text-pink-500",
    gold: "text-amber-500",
    blue: "text-blue-500",
    gray: "text-gray-600",
  };

  return (
    <div className={`w-full ${className}`}>
      {label && (
        <label
          htmlFor={name}
          className="block text-xs uppercase tracking-widest font-bold text-gray-400 mb-2 ml-1"
        >
          {label}{" "}
          {required && (
            <span className={starColors[accentColor] || starColors.pink}>
              *
            </span>
          )}
        </label>
      )}

      <input
        id={name}
        name={name}
        type={type}
        value={value}
        onChange={onChange}
        placeholder={placeholder}
        required={required}
        className="w-full px-5 py-3.5 bg-gray-50 border rounded-xl transition-all outline-none"
      />
    </div>
  );
};

export default FormInput;
