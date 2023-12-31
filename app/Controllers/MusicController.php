<?php

namespace App\Controllers;
use App\Models\PlaylistModel;
use App\Models\PlayListMusicModel;
use App\Models\MusicModel;

class MusicController extends BaseController
{
    public function index()
{
    $musicModel = new MusicModel();
    $musicList = $musicModel->findAll();
    $playlistModel = new PlaylistModel();
    $playlists = $playlistModel->findAll();

    return view('music_player', ['musicList' => $musicList, 'playlists' => $playlists]);
}


    public function createPlaylist()
    {
        $playlistName = $this->request->getPost('playlist_name');

        $playlistModel = new PlaylistModel();
        $playlistModel->insert(['name' => $playlistName]);

        return redirect()->to('/');
    }
    public function getPlaylist($playlistID)
    {
        $playlistModel = new PlaylistModel();
        $playlist = $playlistModel->find($playlistID);
    
        if (!$playlist) {
            return $this->response->setJSON(['error' => 'Playlist not found']);
        }
    
        $playlistMusicModel = new PlaylistMusicModel();
        $musicTrackIDs = $playlistMusicModel->where('playlist_id', $playlistID)->findAll();
    
        if (empty($musicTrackIDs)) {
            return $this->response->setJSON(['error' => 'No music tracks in this playlist']);
        }
    
        $musicModel = new MusicModel();
        $musicTracks = $musicModel->whereIn('id', array_column($musicTrackIDs, 'music_track_id'))->findAll();
    
        return $this->response->setJSON(['playlist' => $playlist, 'musicTracks' => $musicTracks]);
    }
    

    public function uploadMusic()
{
    $musicModel = new MusicModel();

    $file = $this->request->getFile('musicFile');
    $customFileName = $this->request->getPost('musicName'); // Get the custom file name

    if ($file->isValid() && $file->getClientExtension() === 'mp3') {
        $newName = $file->getRandomName();
        $file->move(ROOTPATH . 'public/uploads', $newName);

        // Save the custom file name and file path to the database
        $musicModel->insert([
            'file_name' => $customFileName, // Save the custom file name
            'file_path' => 'uploads/' . $newName,
        ]);

        return redirect()->to('/')->with('success', 'Music uploaded successfully');
    } else {
        return redirect()->to('/music')->with('error', 'Invalid or unsupported file format');
    }
}

    public function addToPlaylist()
    {
        $musicID = $this->request->getPost('musicID');
        $playlistID = $this->request->getPost('playlistID');
    
        
    
        $playlistMusicModel = new PlaylistMusicModel();
        $existingAssociation = $playlistMusicModel->where('playlist_id', $playlistID)
                                                ->where('music_track_id', $musicID)
                                                ->countAllResults();
        
        if ($existingAssociation === 0) {
            $playlistMusicModel->insert([
                'playlist_id' => $playlistID,
                'music_track_id' => $musicID,
            ]);
    
            return redirect()->to('/')->with('success', 'Music added to the playlist.');
        } else {
            return redirect()->to('/')->with('error', 'Music is already in the playlist.');
        }
        return redirect()->to('/')->with('success', 'Music added to the playlist.');
    }


    public function getPlaylistMusic()
{
    $playlistID = $this->request->getPost('playlistID');
    $musicModel = new MusicModel();
    $musicList = $musicModel->where('playlist_id', $playlistID)->findAll();

    return $this->response->setJSON($musicList);
}
public function playlists($playlistID)
{
    // Load the necessary models (PlaylistModel and MusicModel) and make the necessary database queries to fetch playlist details and associated music tracks.

    $playlistModel = new PlaylistModel();
    $musicModel = new MusicModel();

    // Find the playlist by its ID
    $playlist = $playlistModel->find($playlistID);

    if (!$playlist) {
        return redirect()->to('/');
    }

    // Find the music_track_ids associated with the playlist
    $playlistMusicModel = new PlaylistMusicModel();
    $musicTrackIDs = $playlistMusicModel->where('playlist_id', $playlistID)->findAll();

    // Initialize an empty array to store music items
    $music = [];

    // Loop through each music_track_id and find the associated music track
    foreach ($musicTrackIDs as $musicTrackID) {
        $musicTrack = $musicModel->find($musicTrackID['music_track_id']);

        if ($musicTrack) {
            // Add the music_tracks item to the "music" array
            $music[] = $musicTrack;
        }
    }

    // Prepare the data to be passed to the view
    $data = [
        'playlist' => $playlist,
        'musicTracks' => $music,
    ];

    // Render a view named 'player' and pass the data to it
    return view('player', $data);
}


}
