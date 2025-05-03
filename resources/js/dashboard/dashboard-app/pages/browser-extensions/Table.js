import React, { useState, useEffect, useRef } from "react";
import { __ } from "@wordpress/i18n";
import apiFetch from "@wordpress/api-fetch";
import DeleteIcon from "../../../../icons/DeleteIcon";
import Pause from "../../../../icons/Pause";
import Play from "../../../../icons/Play";
import CloseIcon from "../../../../icons/CloseIcon";
import trash from "../../../../images/trash.png";
import { Tooltip } from "antd";

function Table() {
  const [status, setStatus] = useState(false);
  const [tokensData, setTokensData] = useState([]);
  const [offset, setOffset] = useState(0);
  const [modal, setModal] = useState(false);
  const [deleteItem, setDeleteItem] = useState(null);
  const [extendItem, setExtendItem] = useState(null);
  const [extendModal, setExtendModal] = useState(false);
  const [currentTimestamp, setCurrentTimestamp] = useState(0);
  const [loadMoreDisabled, setLoadMoreDisabled] = useState("enabled");
  const [loading, setLoading] = useState("loading");
  const [dataLength, setDataLength] = useState(null);
  const deletedItemRefs = useRef({});
  const expireRefs = useRef({});

  // load data operation
  const loadMore = () => {
    setOffset(offset + 20);
    const formData = new window.FormData();
    formData.append("action", "login_me_now_browser_tokens");
    formData.append("security", lmn_admin.generate_token_nonce);
    formData.append("offset", offset);
    formData.append("limit", 21);

    apiFetch({
      url: lmn_admin.ajax_url,
      method: "POST",
      body: formData,
    })
      .then((data) => {
        setLoading("loadingOver");
        if (data.success) {
          setDataLength(data.data.length);
          setTokensData(tokensData.concat(data.data));
        } else {
          setLoadMoreDisabled("disabled");
        }
      })
      .catch((error) => {
        console.log(error);
      });
  };

  useEffect(() => {
    loadMore();
  }, []);

  // delete modal code
  const handleDelete = (item) => {
    setDeleteItem(item);
    setModal(!modal);
  };

  useEffect(() => {
    if (modal) {
      document.body.classList.add("active-modal");
    } else {
      document.body.classList.remove("active-modal");
    }
  }, [modal]);

  // token delete operation
  const handleDeleteClick = (key) => {
    const formData = new window.FormData();
    formData.append("action", "login_me_now_browser_token_drop");
    formData.append("security", lmn_admin.generate_token_nonce);
    formData.append("token_id", key);

    const itemToDelete = tokensData.find((item) => item.token_id === key);
    if (!itemToDelete) {
      return;
    }

    apiFetch({
      url: lmn_admin.ajax_url,
      method: "POST",
      body: formData,
    })
      .then((data) => {
        if (data.success) {
          const deletedItem = deletedItemRefs.current[key];
          if (deletedItem) {
            deletedItem.classList.add(
              "transition-opacity",
              "duration-1000",
              "ease-linear",
              "opacity-0",
              "h-0",
              "deleted-bg"
            );

            setTimeout(() => {
              setTokensData(tokensData.filter((item) => item.token_id !== key));
            }, 1000);

            setModal(!modal); // Close the modal here
          }
        }
      })
      .catch((error) => {
        console.log(error);
      });
  };

  // status handle operation
  const handleStatus = (key, updateStatus) => {
    const newStatus = updateStatus === "pause" ? "active" : "pause";
    const formData = new window.FormData();
    formData.append("action", "login_me_now_browser_token_update_status");
    formData.append("security", lmn_admin.generate_token_nonce);
    formData.append("token_id", key);
    formData.append("status", newStatus);

    apiFetch({
      url: lmn_admin.ajax_url,
      method: "POST",
      body: formData,
    })
      .then((data) => {
        setStatus(!status);
        const updatedTokens = tokensData.map((item) =>
          item.token_id === key
            ? {
                ...item,
                status: newStatus,
              }
            : item
        );
        setTokensData(updatedTokens);
      })
      .catch((error) => {
        console.log(error);
      });
  };

  // get present date and time in timestamp format to compare expire date
  function presentTimeStamp() {
    const timestamp = Date.now();
    setCurrentTimestamp(timestamp);
  }

  useEffect(() => {
    presentTimeStamp();
    const interval = setInterval(() => {
      presentTimeStamp();
    }, 1000 * 60);

    return () => clearInterval(interval);
  }, []);
  const truncatedTimestamp = Number(currentTimestamp.toString().slice(0, -3));

  // datetime-local previous date disabled
  const getCurrentDateTime = () => {
    const now = new Date();
    const year = now.getFullYear();
    const month = `${now.getMonth() + 1}`.padStart(2, "0");
    const day = `${now.getDate()}`.padStart(2, "0");
    const hours = `${now.getHours()}`.padStart(2, "0");
    const minutes = `${now.getMinutes()}`.padStart(2, "0");

    return `${year}-${month}-${day}T${hours}:${minutes}`;
  };

  return (
    <section aria-labelledby="section-1-title h-full">
      <h2 className="sr-only" id="section-1-title ">
        What’s coming next?
      </h2>
      <div className="p-[2rem] rounded-md bg-white overflow-hidden shadow-sm flex flex-col justify-center h-full">
        <div className="relative w-full flex flex-col sm:flex-row sm:items-center sm:justify-between">
          <span className="font-semibold text-xl leading-6 text-slate-800 mb-4 sm:mb-0">
            {__(`Token`, "login-me-now")}
          </span>
        </div>

        {/* table code start from here */}

        <div className="overflow-y-auto mt-5 h-[47vh] relative table-scrollbar">
          <table class="table-auto w-full">
            <thead className="sticky top-[-1px] bg-[#f1f1f1]">
              <tr>
                <th className="border-x-[1px] border-b-[1px] border-[#e2e8f0] py-[10px] px-[5px] text-[14px]">
                  User{" "}
                  <Tooltip title="Which user has generated this token for browser extension">
                    <svg
                      className="inline-block"
                      fill="#9A9A9A"
                      width="14px"
                      height="14px"
                      viewBox="0 0 24 24"
                      xmlns="http://www.w3.org/2000/svg"
                    >
                      <path d="M12,1A11,11,0,1,0,23,12,11.013,11.013,0,0,0,12,1Zm0,20a9,9,0,1,1,9-9A9.011,9.011,0,0,1,12,21ZM13,8H11V6h2Zm0,10H11V10h2Z" />
                    </svg>
                  </Tooltip>
                </th>
                <th className="border-x-[1px] border-b-[1px] border-[#e2e8f0] py-[10px] px-[5px] text-[14px]">
                  ID{" "}
                  <Tooltip title="Browser extension token ID for activity log reference">
                    <svg
                      className="inline-block"
                      fill="#9A9A9A"
                      width="14px"
                      height="14px"
                      viewBox="0 0 24 24"
                      xmlns="http://www.w3.org/2000/svg"
                    >
                      <path d="M12,1A11,11,0,1,0,23,12,11.013,11.013,0,0,0,12,1Zm0,20a9,9,0,1,1,9-9A9.011,9.011,0,0,1,12,21ZM13,8H11V6h2Zm0,10H11V10h2Z" />
                    </svg>
                  </Tooltip>
                </th>
                <th className="border-x-[1px] border-b-[1px] border-[#e2e8f0] py-[10px] px-[5px] text-[14px]">
                  Created At
                </th>
                <th className="border-x-[1px] border-b-[1px] border-[#e2e8f0] py-[10px] px-[5px] text-[14px]">
                  Expire On
                </th>
                <th className="border-x-[1px] border-b-[1px] border-[#e2e8f0] py-[10px] px-[5px] text-[14px]">
                  Action
                </th>
              </tr>
            </thead>
            {loading === "loading" ? (
              <tbody className="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-full">
                <div className="bg-[#dce5f3] mb-[5px] mx-[8px] rounded-[4px]">
                  <div class="stage">
                    <div class="dot-pulse"></div>
                  </div>
                </div>
              </tbody>
            ) : (
              <tbody>
                {tokensData < 1 ? (
                  <div className="absolute w-full left-1/2 bottom-1/2 transform translate-x-[-50%] translate-y-[50%]">
                    <h1 className="text-[20px] font-bold text-[#023a2e] text-center">
                      No extension token has been created yet!
                    </h1>
                  </div>
                ) : (
                  tokensData &&
                  tokensData.map((item) => (
                    <tr
                      key={item.token_id}
                      ref={(element) =>
                        (deletedItemRefs.current[item.token_id] = element)
                      }
                      className={`border-b-[1px] border-[#e2e8f0] ${
                        item.status === "pause" ? "bg-[#a7e0f8]" : ""
                      }`}
                    >
                      <td className="text-center text-[13px] font-[500] border-x-[1px] border-[#e2e8f0] py-[10px] px-[5px]">
                        {item.display_name}
                        <span className="block text-[#919196]">
                          {item.user_login}
                        </span>
                      </td>
                      <td className="text-center text-[13px] font-[500] border-x-[1px] border-[#e2e8f0] py-[10px] px-[5px]">
                        {item.token_id}
                      </td>
                      <td className="text-center text-[13px] font-[500] border-x-[1px] border-[#e2e8f0] py-[10px] px-[5px]">
                        {new Date(item.created_at * 1000).toLocaleString(
                          "en-US",
                          {
                            year: "numeric",
                            month: "short",
                            day: "numeric",
                            hour: "numeric",
                            minute: "numeric",
                          }
                        )}
                      </td>
                      <td
                        ref={(element) =>
                          (expireRefs.current[item.token_id] = element)
                        }
                        className={`${
                          truncatedTimestamp >= item.expire
                            ? "text-red-600"
                            : ""
                        } text-center text-[13px] font-[500] border-x-[1px] border-[#e2e8f0] py-[10px] px-[5px]`}
                      >
                        {new Date(item.expire * 1000).toLocaleString("en-US", {
                          year: "numeric",
                          month: "short",
                          day: "numeric",
                          hour: "numeric",
                          minute: "numeric",
                        })}
                      </td>
                      <td className="text-center text-[13px] font-[500] border-x-[1px] border-[#e2e8f0] py-[10px] px-[5px]">
                        <span className="flex justify-around">
                          <Tooltip title="Delete">
                            <span
                              className="cursor-pointer"
                              onClick={() => handleDelete(item)}
                            >
                              <DeleteIcon size={18} />
                            </span>
                          </Tooltip>
                          <span
                            onClick={() =>
                              handleStatus(item.token_id, item.status)
                            }
                            className={`${
                              truncatedTimestamp >= item.expire
                                ? "cursor-not-allowed pointer-events-none"
                                : ""
                            }`}
                          >
                            {item.status === "pause" ||
                            truncatedTimestamp >= item.expire ? (
                              <Tooltip title="Inactive">
                                <span className="cursor-pointer">
                                  <Pause
                                    size={18}
                                    color={
                                      truncatedTimestamp >= item.expire
                                        ? "#D11A2A"
                                        : "#2192c0"
                                    }
                                  />
                                </span>
                              </Tooltip>
                            ) : (
                              <Tooltip title="Active">
                                <span className="cursor-pointer disable">
                                  <Play size={18} />
                                </span>
                              </Tooltip>
                            )}
                          </span>
                        </span>
                      </td>
                    </tr>
                  ))
                )}
              </tbody>
            )}
          </table>
        </div>
        {dataLength < 20 ? null : (
          <button
            className="mt-2 sm:inline-flex items-center justify-center px-4 py-2 border border-lmn-hover text-[16px] font-medium rounded-md shadow-sm text-black hover:text-white bg-white focus-visible:bg-lmn-hover hover:bg-lmn-hover focus:outline-none mb-2 sm:mb-0"
            onClick={loadMore}
          >
            Load More
          </button>
        )}

        {/* delete modal start from here */}
        {modal && deleteItem && (
          <div className="modal">
            <div onClick={handleDelete} className="overlay"></div>
            <div className="modal-content">
              <div>
                <div className="flex justify-center">
                  <img src={trash} alt="" />
                </div>
                <h1 className="text-center text-[#000000] my-4 text-[20px] leading-[32px] mb-[34px]">
                  Are you sure you want to delete
                  <br /> your token?
                </h1>
                <div className="flex justify-between">
                  <button
                    className="bg-[#d11a2a] hover:bg-[#ac0412] text-white font-bold py-2 px-4 rounded h-[42px] w-[142px]"
                    onClick={() => handleDeleteClick(deleteItem.token_id)}
                  >
                    Yes, Delete
                  </button>
                  <button
                    className="bg-[#28a745] hover:bg-[#218838] text-white font-bold py-2 px-4 rounded h-[42px] w-[132px]"
                    onClick={handleDelete}
                  >
                    No, keep
                  </button>
                </div>
              </div>
            </div>
          </div>
        )}
      </div>
    </section>
  );
}

export default Table;
