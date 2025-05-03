import React, { useState } from "react";
import browserVideoThumbnail from "../../../../images/browser-extension-thumbnail.png";

const Video = ({ width, height }) => {
  const [isPlaying, setIsPlaying] = useState(false);

  const handleThumbnailClick = () => {
    setIsPlaying(true);
  };

  return (
    <>
      {!isPlaying ? (
        <img
          src={browserVideoThumbnail}
          alt="Video Thumbnail"
          style={{ cursor: "pointer", width, height }}
          onClick={handleThumbnailClick}
        />
      ) : (
        <div>
          <iframe
            title="Login Me Now Browser Extension : 1 click WordPress admin dashboard login"
            width={width}
            height={height}
            src={`https://www.youtube.com/embed/2_GOOzZkeSM?autoplay=1&mute=1&rel=0`}
            frameBorder="0"
            allowFullScreen
          />
        </div>
      )}
    </>
  );
};

export default Video;
