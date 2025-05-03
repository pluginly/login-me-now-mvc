import React from "react";
import { __ } from "@wordpress/i18n";

function SubTitle({text, size}) {
  return (
    <p className={`text-[16px] leading-[1.625rem] text-slate-600 pb-7 text-justify ${size}`}>

      {__(
        `${text}`,
        "login-me-now"
      )}
    </p>
  );
}

export default SubTitle;
