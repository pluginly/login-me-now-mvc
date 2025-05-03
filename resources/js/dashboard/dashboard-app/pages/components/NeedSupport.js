import React from "react";
import { __ } from "@wordpress/i18n";

function NeedSupport({ title, subtitle, link, classNames, buttonText, icon }) {
  return (
    <section aria-labelledby="section-2-title">
      <h2 className="sr-only" id="section-2-title">
        Need Support
      </h2>
      <div
        className={`relative box-border ${classNames} border border-[#9F9F9F] bg-[#f6f6f6] rounded-[8px] shadow-sm overflow-hidden transition hover:shadow-hover`}
      >
        <div className="p-6">
          <h3 className="relative flex items-center text-slate-800 text-base font-semibold pb-2">
            <span className="flex-1 text-black text-[16px]">{__(`${title}`, "login-me-now")}</span>
          </h3>
          <p className="text-[#6B6D71] text-[15px] font-medium leading-7 pb-5 ">
            {__(`${subtitle}`, "login-me-now")}
          </p>
          <a
            className="text-[#007cba] text-decoration-color-[#007cba] text-base font-medium underline flex items-center"
            href={`${link}`}
            target="_blank"
            rel="noreferrer"
          >
            {__(`${buttonText}`, "login-me-now")}
              {icon}
          </a>
        </div>
      </div>
    </section>
  );
}

export default NeedSupport;
