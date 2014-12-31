<?php exit() ?>--by ikita 183.178.170.22
function OnLoad()
--print(GetMap().index)
gameState = GetGame()
newPath = SCRIPT_PATH .. "announcer_dlc_glados\\"
newPath = string.gsub(newPath, "\\", "/" )
newPath = string.gsub(newPath, "/", "\\\\" )
secTime = nil
sound30played = false
sound10played = false
sound5played = false
sound0played = false
soundflplayed = false
soundwinplayed = false
vicfl1played = false
vicfl2played = false
currentMap = GetGame().map.index
winTime = 0
--loops from 1 to max
respawnTable = {
"ann_glados_followup_respaw_01.wav", 
"ann_glados_followup_respaw_02.wav", 
"ann_glados_followup_respaw_03.wav", 
"ann_glados_followup_respaw_04.wav", 
"ann_glados_followup_respaw_05.wav", 
"ann_glados_followup_respaw_06.wav", 
"ann_glados_followup_respaw_07.wav", 
"ann_glados_followup_respaw_08.wav", 
"ann_glados_followup_respaw_09.wav", 
"ann_glados_followup_respaw_13.wav", 
"ann_glados_followup_respaw_14.wav", 
"ann_glados_followup_respaw_16.wav", 
"ann_glados_followup_respaw_17.wav"
}

thirtySecondTable = {
"ann_glados_count_battle_30_01.wav",
"ann_glados_count_battle_30_02.wav"
}

tenSecondTable = {
"ann_glados_count_battle_10_01.wav",
"ann_glados_count_battle_10_02.wav"
}

battleBeginsTable = {
"ann_glados_battle_begin_01.wav",
"ann_glados_battle_begin_02.wav",
"ann_glados_battle_begin_03.wav",
"ann_glados_battle_begin_04.wav"
}

victoryTable = {
"ann_glados_victory_01.wav"
}

defeatTable = {
"ann_glados_defeat_01.wav"
}

victoryBlueRad = {
"ann_glados_victory_rad_01.wav",
"ann_glados_victory_rad_02.wav",
"ann_glados_victory_rad_03.wav",
"ann_glados_victory_rad_long_01.wav"
}

victoryPurpleDire = {
"ann_glados_victory_dire_01.wav",
"ann_glados_victory_dire_02.wav",
"ann_glados_victory_dire_03.wav",
"ann_glados_victory_dire_long_01.wav"
}

victoryFollow = {
"ann_glados_vict_follow_01.wav",
"ann_glados_vict_follow_02.wav",
"ann_glados_vict_follow_03.wav",
"ann_glados_vict_follow_04.wav",
"ann_glados_vict_follow_05.wav"
}



end

function OnReady()
	secTime = GetTickCount()/1000
	print("GLaDOS Announcer Pack Loaded -- by ikita")
end


function OnTick()
	if currentMap == 12 then
	
	-----------ARAM
		if secTime ~= nil and GetTickCount()/1000 - secTime > 27 and GetTickCount()/1000 - secTime < 33 and sound30played == false then
			PlaySoundPS(newPath .. thirtySecondTable[math.random(#thirtySecondTable)], 1000)
			sound30played = true
		end
		
		--10 seconds to battle
		if secTime ~= nil and GetTickCount()/1000 - secTime > 47 and GetTickCount()/1000 - secTime < 53 and sound10played == false then
			PlaySoundPS(newPath .. tenSecondTable[math.random(#tenSecondTable)], 500)
			sound10played = true
		end
		
		-- battle 5 seconds
		if secTime ~= nil and GetTickCount()/1000 - secTime > 52 and GetTickCount()/1000 - secTime < 58 and sound5played == false then
			PlaySoundPS(newPath .. "ann_glados_count_battle_01.wav", 500)
			sound5played = true
		end
		
		--begin battle
		if secTime ~= nil and GetTickCount()/1000 - secTime > 57 and GetTickCount()/1000 - secTime < 63 and sound0played == false then
			PlaySoundPS(newPath .. battleBeginsTable[math.random(#battleBeginsTable)], 1000)
			sound0played = true
		end
		
		
		--begin battle follow
		if secTime ~= nil and GetTickCount()/1000 - secTime > 70 and GetTickCount()/1000 - secTime < 76 and soundflplayed == false then
			if math.random() > 0.5 then
				PlaySoundPS(newPath .. "ann_glados_battle_begin_follow.wav", 1000)
			end
			soundflplayed = true
		end
	--------------------------

	else
	
----------SUMMONERS RIFT	
		--print(GetTickCount()/1000)
		--30 seconds to battle
		if secTime ~= nil and GetTickCount()/1000 - secTime > 57 and GetTickCount()/1000 - secTime < 63 and sound30played == false then
			PlaySoundPS(newPath .. thirtySecondTable[math.random(#thirtySecondTable)], 1000)
			sound30played = true
		end
		
		--10 seconds to battle
		if secTime ~= nil and GetTickCount()/1000 - secTime > 77 and GetTickCount()/1000 - secTime < 83 and sound10played == false then
			PlaySoundPS(newPath .. tenSecondTable[math.random(#tenSecondTable)], 500)
			sound10played = true
		end
		
		-- battle 5 seconds
		if secTime ~= nil and GetTickCount()/1000 - secTime > 82 and GetTickCount()/1000 - secTime < 88 and sound5played == false then
			PlaySoundPS(newPath .. "ann_glados_count_battle_01.wav", 500)
			sound5played = true
		end
		
		--begin battle
		if secTime ~= nil and GetTickCount()/1000 - secTime > 87 and GetTickCount()/1000 - secTime < 93 and sound0played == false then
			PlaySoundPS(newPath .. battleBeginsTable[math.random(#battleBeginsTable)], 1000)
			sound0played = true
		end
		
		
		--begin battle follow
		if secTime ~= nil and GetTickCount()/1000 - secTime > 100 and GetTickCount()/1000 - secTime < 106 and soundflplayed == false then
			if math.random() > 0.5 then
				PlaySoundPS(newPath .. "ann_glados_battle_begin_follow.wav", 1000)
			end
			soundflplayed = true
		end
	
	end
	
	-- end of game
	if gameState.isOver then
		if gameState.win and soundwinplayed == false then
			PlaySoundPS(newPath .. defeatTable[math.random(#defeatTable)], 500)
			soundwinplayed = true
			winTime = GetTickCount()
		elseif gameState.win == false and soundwinplayed == false then
			PlaySoundPS(newPath .. defeatTable[math.random(#defeatTable)], 500)
			soundwinplayed = true
		end
		--print(gameState.winner)
	end
	--follow up
	if gameState.isOver then
		if math.random() > 0.5 then
			if GetTickCount() - winTime > 3000 and vicfl1played == false then
				if gameState.winner == TEAM_BLUE then
					PlaySoundPS(newPath .. victoryBlueRad[math.random(#victoryBlueRad)], 500)
					vicfl1played = true
				end
				if gameState.winner == TEAM_RED then
					PlaySoundPS(newPath .. victoryPurpleDire[math.random(#victoryPurpleDire)], 500)
					vicfl1played = true
				end			
			end
			if GetTickCount() - winTime > 10000 and vicfl2played == false and gameState.win then	
				PlaySoundPS(newPath .. victoryFollow[math.random(#victoryFollow)], 500)
				vicfl2played = true
			end
		else
			if GetTickCount() - winTime > 3000 and vicfl2played == false and gameState.win then	
				PlaySoundPS(newPath .. victoryFollow[math.random(#victoryFollow)], 500)
				vicfl2played = true
			end
		end
	end
	
	
	
end

function OnCreateObj(object)
--print(object.name)
	--if object.name == "SpawnBeacon.troy" then
		--secTime = GetTickCount()/1000
	--end
	if object.name == "Respawn_glb.troy" then
		if GetDistance(object) < 50 then
			PlaySoundPS(newPath .. respawnTable[math.random(#respawnTable)], 1000)
		end
	end
end