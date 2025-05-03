import React from "react";
import { __ } from "@wordpress/i18n";

function Title({ text }) {
  return (
    <div className="flex">
      <h2 className="text-slate-800 text-[22px] leading-10 pb-3 font-semibold text-left">
        {__(`${text}`, "login-me-now")}
      </h2>
    </div>
  );
}

export default Title;
