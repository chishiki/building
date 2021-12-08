SET @now := now();

REPLACE INTO perihelion_Lang VALUES
('adminBuildingList', 'Building List', 0, '物件一覧', 0, @now),
('buildingName', 'Building Name', 0, '物件名', 0, @now),
('buildingPublished', 'Published', 0, '公開', 0, @now),
('buildingURL', 'URL', 0, 'URL', 0, @now),
('buildingDescription', 'Description', 0, '明細', 0, @now),
('buildingImages', 'Images', 0, 'イメージ', 0, @now),
('buildingFiles', 'Files', 0, 'ファイル', 0, @now),
('buildingCreate', 'Add Building', 0, '物件新規追加', 0, @now),
('buildingUpdate', 'Update Building', 0, '物件更新', 0, @now),
('building', 'Building', 0, '物件', 0, @now),
('buildingCreateSuccessful', 'Building Create Successful', 0, '物件は追加済みです。', 0, @now),
('buildingUpdateSuccessful', 'Building Update Successful', 0, '物件は更新済みです。', 0, @now),
('buildingConfirmDelete', 'Confirm Building Delete', 0, '物件削除確認', 0, @now),
('confirmDelete', 'Confirm Delete', 0, '削除確認', 0, @now),
('buildingDeleteSuccessful', 'Building Deleted Successfully', 0, '物件は削除済みです。', 0, @now);

-- INSERT INTO perihelion_Lang VALUES ('xxxxxxx', 'xxxxxxx', 0, 'xxxxxxx', 0, @now);

