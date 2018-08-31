Insert INTO Users
(Username,Password_Hash,User_Active,User_Admin,User_First_Name,User_Email)
VALUES
('test','test','1','0','Admin','web@serverintl.net');

INSERT INTO Urls
(Url_Path, Url_Link, Url_User_Id, Url_Active)
VALUES
('/google','https://google.com','1','1');
