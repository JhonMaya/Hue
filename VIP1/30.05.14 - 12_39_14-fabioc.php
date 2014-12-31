<?php exit() ?>--by fabioc 188.82.177.11
local currentRecalls = {}
local ModeOfRecall = {}
local Saved = GetSave("Camps2")
local LastPing = 0
local LastChat = 0
local ID = myHero.networkID..myHero.charName
	for i, enemy in ipairs(GetEnemyHeroes()) do
			ID =  ID..enemy.networkID
	end

 if not tostring(Saved["gameid"]):find(ID) then
		
		Saved["gameid"] = ID
		Saved["camps"] = 
		{	-- Camp name 			Camp side  				 Map Position 									UI position 									ID 				JungleTimers		NextRespawn			 FirstSpawn
			--BLUE ["side"]		
			{["cname"] = "Red", 	["side"] = TEAM_BLUE, 	 ["position"] = {["x"] = 7420, ["z"] =   3733},	["sposition"] = {["x"] = 500, ["z"] =  1025}, 	["Id"] = 4, 	["Rate"] = 5*60, 	["NextRespawn"] = 0, ["FirstSpawn"] = 115},
			{["cname"] = "Blue", 	["side"] = TEAM_BLUE,	 ["position"] = {["x"] = 3647, ["z"] =  7572}, 	["sposition"] = {["x"] = 500, ["z"] =  1050},	["Id"] = 1, 	["Rate"] = 5*60, 	["NextRespawn"] = 0, ["FirstSpawn"] = 115},
			
			--RED ["side"]	
			{["cname"] = "Red", 	["side"] = TEAM_RED, 	 ["position"] = {["x"] = 6436, ["z"] =  10524}, ["sposition"] = {["x"] = 1380, ["z"] =  1025},	["Id"] = 10, 	["Rate"] = 5*60, 	["NextRespawn"] = 0, ["FirstSpawn"] = 115},
			{["cname"] = "Blue", 	["side"] = TEAM_RED, 	 ["position"] = {["x"] = 10480,["z"] =  6860}, 	["sposition"] = {["x"] = 1380, ["z"] =  1050},	["Id"] = 7, 	["Rate"] = 5*60, 	["NextRespawn"] = 0, ["FirstSpawn"] = 115},

		
		}
		NewGame = true
end

function OnLoad()
	local Camps = Saved["camps"]
	ModeOfRecall = {Recall = 8000, RecallImproved = 7000, OdinRecall = 4500, OdinRecallImproved = 4000}

	scriptMenu = scriptConfig("OPTracker", "OPTracker")
		scriptMenu:addSubMenu("Jungle Tracker", "JungleT")
			scriptMenu.JungleT:addParam("ShowMsg", "Show Msg", SCRIPT_PARAM_ONOFF, true)

		scriptMenu:addSubMenu("Recall Tracker", "RecallT")
			scriptMenu.RecallT:addParam("ShowRecall", "Show Msg", SCRIPT_PARAM_ONOFF, true)
			scriptMenu.RecallT:addParam("Teamcheck", "Only enemys", SCRIPT_PARAM_ONOFF, true)
end


function OnTick()
	local Camps = Saved["camps"]
end
	
Packet.headers.PKT_S2C_Neutral_Camp_Empty = 194
function OnRecvPacket(p)
	local Camps = Saved["camps"]
	if p.header == Packet.headers.PKT_S2C_Neutral_Camp_Empty then
		local packet = Packet(p)

		if packet:get("emptyType") ~= 3 then
			for i, camp in ipairs(Camps) do
				if camp["Id"] == packet:get("campId") then
					Camps[i]["NextRespawn"] = os.clock() + camp["Rate"]
				end
			end
		end
	end
	
	if p.header == 0x17 then
		p.pos = 1
		local networkID = p:DecodeF()
		local object1 = objManager:GetObjectByNetworkId(networkID)
		p.pos = 7
		if object1 and object1.valid and (not object1.visible ) then
			local name = ""
			for i=7, p.size-9, 1 do
				name = name .. string.char(p:Decode1())
			end
			
			if ("BlessingOfTheLizardElderLines"):find(name) then
				local Dist = math.huge
				local id = 0
				for i, camp in ipairs(Camps) do
					if camp["cname"] == "Red" and camp["NextRespawn"] <= os.clock() and (GetDistance(object1, Vector(camp["position"]["x"],0,camp["position"]["z"])) < Dist) then
						 id = i
						 Dist = GetDistance(object1, Vector(camp["position"]["x"],0,camp["position"]["z"]))
					end
				end
				if id ~= 0 then
					Camps[id]["NextRespawn"] = os.clock() + 10 + Camps[id]["Rate"]
					if scriptMenu.JungleT.ShowMsg then
						PrintChat("Red has been killed!")
					end
				end
			end
			
			if ("CrestOfTheAncientGolemLines"):find(name) then
				local Dist = math.huge
				local id = 0
				for i, camp in ipairs(Camps) do
					if camp["cname"] == "Blue" and camp["NextRespawn"] <= os.clock() and (GetDistance(object1, Vector(camp["position"]["x"],0,camp["position"]["z"])) < Dist) then
						 id = i
						 Dist = GetDistance(object1, Vector(camp["position"]["x"],0,camp["position"]["z"]))
					end
				end
				if id ~= 0 then
					Camps[id]["NextRespawn"] = os.clock() + 10 + Camps[id]["Rate"]
					if scriptMenu.JungleT.ShowMsg then
						PrintChat("Blue has been killed!")
					end
				end
			end
		end
	elseif p.header == 0xE8 then 
	
		p.pos = 5
		local x = p:DecodeF()
		local y = p:DecodeF()
		local z = p:DecodeF()
		p.pos = p.size - 3
		local num = p:Decode1()
		
		for i, camp in ipairs(Camps) do
			if camp["Id"] == num then
				Camps[i]["NextRespawn"] = os.clock() - 1
			end
		end
				
	end
	if scriptMenu.RecallT.ShowRecall then
		if scriptMenu.RecallT.Teamcheck then
			if p.header == 0xD7 then
				p.pos = 5
				local RecallSource = p:DecodeF()
				local RecallingChampion = objManager:GetObjectByNetworkId(RecallSource)
				p.pos = 112
				local RecallingType = p:Decode1()

				if RecallingChampion.team == myHero.team or RecallingChampion == myHero.networkID then return end
				if currentRecalls[RecallSource] and RecallingType == 4 then
					local String = (os.clock()  > currentRecalls[RecallSource].finish and "Finished!" or "Canceled!")
					PrintChat(RecallingChampion.charName .. " recall status: "..String)
					currentRecalls[RecallSource] = nil
				elseif currentRecalls[RecallSource] == nil and RecallingType == 6 then
					currentRecalls[RecallSource] = {object = RecallingChampion, time = os.clock(), finish = os.clock() + ModeOfRecall[RecallingChampion:GetSpellData(RECALL).name], rTime = ModeOfRecall[RecallingChampion:GetSpellData(RECALL).name]}
					PrintChat(RecallingChampion.charName .. " recall status: Started")
				end
			end
		elseif not scriptMenu.RecallT.Teamcheck then
				if p.header == 0xD7 then
				p.pos = 5
				local RecallSource = p:DecodeF()
				local RecallingChampion = objManager:GetObjectByNetworkId(RecallSource)
				p.pos = 112
				local RecallingType = p:Decode1()


				if currentRecalls[RecallSource] and RecallingType == 4 then
					local String = (os.clock() > currentRecalls[RecallSource].finish and "Finished!" or "Canceled!")
					PrintChat(RecallingChampion.charName .. " recall status: "..String)
					currentRecalls[RecallSource] = nil
				elseif currentRecalls[RecallSource] == nil and RecallingType == 6 then
					currentRecalls[RecallSource] = {object = RecallingChampion, time = os.clock(), finish = os.clock() + ModeOfRecall[RecallingChampion:GetSpellData(RECALL).name], rTime = ModeOfRecall[RecallingChampion:GetSpellData(RECALL).name]}
					PrintChat(RecallingChampion.charName .. " recall status: Started")
				end
			end
		end
	end
end

function OnReady()
	if NewGame then
		local Camps = Saved["camps"]
		for i, camp in ipairs(Camps) do
			Camps[i]["NextRespawn"] = os.clock() + Camps[i]["FirstSpawn"]
		end
	end
end
