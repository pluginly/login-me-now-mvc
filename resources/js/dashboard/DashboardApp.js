import React from 'react';
import ReactDOM from 'react-dom';

/* Main Component */
import './common/all-config.scss';
import './common/common';
import './custom-css/global.css';
import SettingsWrap from '@DashboardApp/SettingsWrap';
import { Provider } from 'react-redux';
import globalDataStore from '@Admin/store/globalDataStore';
import setInitialState  from '@Utils/setInitialState';

const currentState = globalDataStore.getState();

if ( ! currentState.initialStateSetFlag ) {
	setInitialState( globalDataStore );
}

document.addEventListener('DOMContentLoaded', () => {
	const mountNode = document.getElementById('login-me-now-dashboard-app');
	if (mountNode) {
		ReactDOM.render(
		<Provider store={globalDataStore}>
			<SettingsWrap />
		</Provider>,
		mountNode
		);
	}
});