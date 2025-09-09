/* Avatar Upload Styles */
.avatar-upload {
    position: relative;
    max-width: 120px;
    margin: 0 auto;
}

.avatar-edit {
    position: absolute;
    right: 12px;
    z-index: 1;
    top: 10px;
}

.avatar-edit input {
    display: none;
}

.avatar-edit label {
    display: inline-block;
    width: 34px;
    height: 34px;
    margin-bottom: 0;
    border-radius: 100%;
    background: #ffffff;
    border: 1px solid transparent;
    box-shadow: 0px 2px 4px 0px rgba(0, 0, 0, 0.12);
    cursor: pointer;
    font-weight: normal;
    transition: all .2s ease-in-out;
    display: flex;
    align-items: center;
    justify-content: center;
}

.avatar-edit label:hover {
    background: #f1f1f1;
    border-color: #d6d6d6;
}

.avatar-preview {
    width: 120px;
    height: 120px;
    position: relative;
    border-radius: 50%;
    border: 6px solid #f8f8f8;
    box-shadow: 0px 2px 4px 0px rgba(0, 0, 0, 0.1);
    overflow: hidden;
}

.avatar-preview > div {
    width: 100%;
    height: 100%;
    border-radius: 50%;
    background-size: cover;
    background-repeat: no-repeat;
    background-position: center;
    overflow: hidden;
}
