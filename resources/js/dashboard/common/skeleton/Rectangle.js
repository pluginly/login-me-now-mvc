import React from 'react';

import './Rectangle.scss';

function RectSkeleton( props ) {
	const { width, height, style } = props;

	return (
		<div
			className="ast-skeleton ast-skeleton--rect ast-skeleton--wave"
			style={ {
				width,
				height,
				...style,
			} }
		></div>
	);
}

export default RectSkeleton;
