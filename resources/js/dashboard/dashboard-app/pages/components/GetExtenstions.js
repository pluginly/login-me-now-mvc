import React from "react";
import { __ } from "@wordpress/i18n";
import chromeImg from "../../../../images/chrome.png";
import fireFoxImg from "../../../../images/firefox.png";
import edgeImg from "../../../../images/microsoft.png";
import operaImg from "../../../../images/opera.png";
import braveImg from "../../../../images/brave.png";
import SubTitle from "./SubTitle";

function GetExtenstions() {
  return (
    <section aria-labelledby="section-2-title">
      <h2 className="sr-only" id="section-2-title">
        Install extension
      </h2>
      <div className="box-border rounded-md border border-[#9F9F9F] bg-[#f6f6f6] shadow-sm overflow-hidden transition hover:shadow-hover">
        <div className="p-6">
          <h3 className="text-slate-800 text-base font-semibold pb-2">
            {__("Install extension", "login-me-now")}
          </h3>
          <SubTitle
            size="!text-[15px]"
            text="Easily access the dashboard panel with just one click"
          />

          <div className="flex justify-between">
            <a
              href="https://chrome.google.com/webstore/detail/login-me-now-wordpress-lo/kkkofomlfhbepmpiplggmfpomdnkljoh"
              target="_blank"
              className="mx-2 hover:scale-125 transition-transform duration-300"
            >
              <div class="mb-2 sm:mb-0 flex flex-col items-center">
                <div>
                  <img src={chromeImg} alt="" className="max-h-[50px] w-auto" />
                </div>
              </div>
            </a>
            <a
              href="https://addons.mozilla.org/en-US/firefox/addon/login-me-now/"
              target="_blank"
              className="mx-2 hover:scale-125 transition-transform duration-300"
            >
              <div class="mb-2 sm:mb-0 flex flex-col items-center">
                <div>
                  <img src={fireFoxImg} alt="" className="max-h-[50px] w-auto" />
                </div>
              </div>
            </a>

            <a
              href="https://chrome.google.com/webstore/detail/login-me-now-wordpress-lo/kkkofomlfhbepmpiplggmfpomdnkljoh"
              target="_blank"
              className="mx-2 hover:scale-125 transition-transform duration-300"
            >
              <div class="mb-2 sm:mb-0 flex flex-col items-center">
                <div>
                  <img src={edgeImg} alt="" className="max-h-[50px] w-auto" />
                </div>
              </div>
            </a>
            <a
              href="https://chrome.google.com/webstore/detail/login-me-now-wordpress-lo/kkkofomlfhbepmpiplggmfpomdnkljoh"
              target="_blank"
              className="mx-2 hover:scale-125 transition-transform duration-300"
            >
              <div class="mb-2 sm:mb-0 flex flex-col items-center">
                <div>
                  <img src={operaImg} alt="" className="max-h-[50px] w-auto" />
                </div>
              </div>
            </a>
            <a
              href="https://chrome.google.com/webstore/detail/login-me-now-wordpress-lo/kkkofomlfhbepmpiplggmfpomdnkljoh"
              target="_blank"
              className="mx-2 hover:scale-125 transition-transform duration-300"
            >
              <div class="mb-2 sm:mb-0 flex flex-col items-center">
                <div>
                  <img src={braveImg} alt="" className="max-h-[50px] w-auto" />
                </div>
              </div>
            </a>
          </div>
        </div>
      </div>
    </section>
  );
}

export default GetExtenstions;
