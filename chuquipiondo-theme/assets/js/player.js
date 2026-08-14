/**
 * Music player: HTML5 audio controls + sticky mini player.
 */

(function () {
	'use strict';

	document.addEventListener('DOMContentLoaded', function () {
		initPlayers();
		initMiniPlayer();
	});

	var currentAudio = null; // Track the currently playing audio element.

	/**
	 * Initialize all music players on the page.
	 */
	function initPlayers() {
		var players = document.querySelectorAll('.music-player');
		players.forEach(function (player) {
			var playBtn = player.querySelector('.music-player__play');
			var barFill = player.querySelector('.music-player__bar-fill');
			var bar = player.querySelector('.music-player__bar');
			var currentTime = player.querySelector('.music-player__time--current');
			var durationTime = player.querySelector('.music-player__time--duration');
			var muteBtn = player.querySelector('.music-player__mute');
			var volumeRange = player.querySelector('.music-player__volume-range');
			var iconPlay = player.querySelector('.icon-play');
			var iconPause = player.querySelector('.icon-pause');

			// Create an audio element per player.
			var audio = new Audio();
			audio.preload = 'metadata';
			audio.src = player.getAttribute('data-music-src');

			playBtn.addEventListener('click', function () {
				togglePlay(player, audio, playBtn, iconPlay, iconPause);
			});

			audio.addEventListener('loadedmetadata', function () {
				if (durationTime) {
					durationTime.textContent = formatTime(audio.duration);
				}
			});

			audio.addEventListener('timeupdate', function () {
				if (barFill && audio.duration) {
					var pct = (audio.currentTime / audio.duration) * 100;
					barFill.style.width = pct + '%';
				}
				if (currentTime) {
					currentTime.textContent = formatTime(audio.currentTime);
				}
			});

			audio.addEventListener('ended', function () {
				setPlayingState(playBtn, iconPlay, iconPause, false);
				if (barFill) {
					barFill.style.width = '0%';
				}
			});

			if (bar) {
				bar.addEventListener('click', function (e) {
					var rect = bar.getBoundingClientRect();
					var pct = (e.clientX - rect.left) / rect.width;
					if (audio.duration) {
						audio.currentTime = pct * audio.duration;
					}
				});
			}

			if (muteBtn) {
				muteBtn.addEventListener('click', function () {
					audio.muted = !audio.muted;
					muteBtn.classList.toggle('is-muted', audio.muted);
				});
			}

			if (volumeRange) {
				volumeRange.addEventListener('input', function () {
					audio.volume = parseFloat(volumeRange.value);
				});
			}

			player._audio = audio;
		});
	}

	/**
	 * Toggle play/pause for a player, syncing the mini player.
	 */
	function togglePlay(player, audio, playBtn, iconPlay, iconPause) {
		// Pause any other playing audio.
		if (currentAudio && currentAudio !== audio) {
			currentAudio.pause();
			document.querySelectorAll('.music-player').forEach(function (p) {
				if (p._audio === currentAudio) {
					var pb = p.querySelector('.music-player__play');
					var ip = p.querySelector('.icon-play');
					var ips = p.querySelector('.icon-pause');
					setPlayingState(pb, ip, ips, false);
				}
			});
		}

		if (audio.paused) {
			audio.play();
			setPlayingState(playBtn, iconPlay, iconPause, true);
			currentAudio = audio;
			syncMiniPlayer(player);
		} else {
			audio.pause();
			setPlayingState(playBtn, iconPlay, iconPause, false);
		}
	}

	/**
	 * Update the play/pause icon visibility.
	 */
	function setPlayingState(playBtn, iconPlay, iconPause, isPlaying) {
		if (iconPlay && iconPause) {
			iconPlay.hidden = isPlaying;
			iconPause.hidden = !isPlaying;
		}
		playBtn.setAttribute('aria-label', isPlaying ? 'Pausar' : 'Reproducir');
	}

	/**
	 * Sync the sticky mini player with the current song.
	 */
	function syncMiniPlayer(player) {
		var mini = document.getElementById('chuqui-mini-player');
		if (!mini || !window.chuquipiondoMusic || !window.chuquipiondoMusic.isMiniPlayer) {
			return;
		}

		mini.hidden = false;
		mini.setAttribute('aria-hidden', 'false');

		var title = player.getAttribute('data-music-title');
		var artist = player.getAttribute('data-music-artist');
		var cover = player.getAttribute('data-music-cover');

		var titleEl = mini.querySelector('.music-mini-player__title');
		var artistEl = mini.querySelector('.music-mini-player__artist');
		var coverImg = mini.querySelector('.music-mini-player__cover-img');

		if (titleEl) { titleEl.textContent = title; }
		if (artistEl) { artistEl.textContent = artist; }
		if (coverImg && cover) { coverImg.src = cover; }

		var miniAudio = mini.querySelector('.music-mini-player__audio');
		var miniPlay = mini.querySelector('.music-mini-player__play');
		var audio = player._audio;

		// Bind mini play to the main audio.
		miniPlay.onclick = function () {
			if (audio.paused) {
				audio.play();
			} else {
				audio.pause();
			}
		};
		audio.addEventListener('play', function () {
			miniPlay.innerHTML = '<svg viewBox="0 0 24 24" aria-hidden="true"><path fill="currentColor" d="M6 5h4v14H6zM14 5h4v14h-4z"/></svg>';
		});
		audio.addEventListener('pause', function () {
			miniPlay.innerHTML = '<svg viewBox="0 0 24 24" aria-hidden="true"><path fill="currentColor" d="M8 5v14l11-7z"/></svg>';
		});
	}

	/**
	 * Mini player close button.
	 */
	function initMiniPlayer() {
		var mini = document.getElementById('chuqui-mini-player');
		if (!mini) {
			return;
		}
		var closeBtn = mini.querySelector('.music-mini-player__close');
		if (closeBtn) {
			closeBtn.addEventListener('click', function () {
				if (currentAudio) {
					currentAudio.pause();
				}
				mini.hidden = true;
				mini.setAttribute('aria-hidden', 'true');
			});
		}
	}

	/**
	 * Format seconds as M:SS.
	 */
	function formatTime(seconds) {
		if (isNaN(seconds)) {
			return '0:00';
		}
		var mins = Math.floor(seconds / 60);
		var secs = Math.floor(seconds % 60);
		return mins + ':' + (secs < 10 ? '0' : '') + secs;
	}
})();
