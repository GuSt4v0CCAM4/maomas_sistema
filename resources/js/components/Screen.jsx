import React from 'react';
import ReactDOM from 'react-dom/client';
const styles = {
    Screen: {
        backgroundColor: '#ffffff',
    }
}
function Screen(props) {
    return (
        <div className="screen" style={styles.Screen}>
            {props.children}
        </div>
    );
}
export default Screen;
if (document.getElementById('example')) {
    const Index = ReactDOM.createRoot(document.getElementById("example"));

    Index.render(
        <React.StrictMode>
                <Screen/>
        </React.StrictMode>
    )
}
