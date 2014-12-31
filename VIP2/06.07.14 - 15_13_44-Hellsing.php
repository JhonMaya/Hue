<?php exit() ?>--by Hellsing 31.150.102.226
version = "10(2014-07-06)"
local wardstable = {}
local wtable = {}
local count = 0
local lol = 0
local wardmap, wardtext, wardminipink, wardminigreen = ARGB(150, 133, 255, 10), ARGB(255, 233, 255, 10), ARGB(255, 247, 0, 255), ARGB(255, 0, 255, 0)
local recmap, recmini = ARGB(150, 133, 255, 10), ARGB(255, 133, 255, 10)
local blink = true
local wardtypes = {[0] = "sight/pink", "trinket/lantern", "trinket", "trinket", "sight", "pink", "upgraded trinket", "lantern"}
local wardtimes = {[0] = 180.2, 60.2, 60.2, 61.5, 180.2, 0, 120.2, 180.2}
local functimer=0
local functimer2=0
local functimer3=0
local wardstable = {}
local way = WayPointManager()
local rtimes = {Recall = 8, RecallImproved = 7, OdinRecall = 4.5, OdinRecallImproved = 4}
local rchamps = {}
local enemies=GetEnemyHeroes()
local allies=GetAllyHeroes()
local me=GetMyHero()
local Config = scriptConfig("Ultimate Awareness by Krystian", "aw")
function OnLoad()
	Config:addSubMenu("Wards tracker", "wardst")
	Config:addSubMenu("Waypoints drawer", "wayp")
	Config:addSubMenu("Recalling enemies", "rec")
	Config:addSubMenu("Misc", "misc")
	Config.wardst:addParam("enemy", "Track enemies wards", SCRIPT_PARAM_ONOFF, true)
	Config.wardst:addParam("ally", "Track allies wards", SCRIPT_PARAM_ONOFF, false)
	Config.wardst:addParam("mini", "Show wards on the minimap", SCRIPT_PARAM_ONOFF, true)
	Config.wardst:addParam("fow", "Inform about wards placed in FOW", SCRIPT_PARAM_ONOFF, true)
	Config.wayp:addParam("enemy", "Draw enemies waypoints", SCRIPT_PARAM_ONOFF, true)
	Config.wayp:addParam("ally", "Draw allies waypoints", SCRIPT_PARAM_ONOFF, false)
	Config.rec:addParam("max", "Show on the map", SCRIPT_PARAM_ONOFF, true)
	Config.rec:addParam("mini", "Show on the minimap", SCRIPT_PARAM_ONOFF, true)
	Config.rec:addParam("char", "Print in your chat", SCRIPT_PARAM_ONOFF, true)
	Config.misc:addParam("drawop", "Drawing optimization", SCRIPT_PARAM_ONOFF, true)
	print("Ultimate Awareness v"..version.." loaded.")
end
function OnUnload()
	print("Ultimate Awareness by Krystian unloaded.")
end
function OnTick()

	local gametime = GetInGameTimer()
	if gametime > 3+functimer then
		functimer = gametime
		CheckObj()
	end
	if gametime > 1+functimer2 then
		functimer2 = gametime
		Lantern(functimer2)
		NotFresh(functimer2)
	end
	if gametime > 0.2+functimer3 then
		OptimizeDrawing()
		functimer3 = gametime
	end
end
function OnDraw()
	tim = GetInGameTimer()
	for i=0, 50, 1 do
		if wardstable[i] ~= nil then
			if wardstable[i].positionknown and wardstable[i].isfresh == false then
				if wardstable[i].isdrawn or not Config.misc.drawop then
					DrawCircle3D(wardstable[i].wardx, wardstable[i].wardy, wardstable[i].wardz, 50, 2, wardmap, 10) 
					DrawText3D(wardstable[i].timeleft..wardstable[i].champ.charName, wardstable[i].wardx-80, wardstable[i].wardy, wardstable[i].wardz , 15, wardmini, false)
				end
				if Config.wardst.mini then
					DrawRectangle(GetMinimapX(wardstable[i].wardx)-3, GetMinimapY(wardstable[i].wardz)-3, 9, 9, ARGB(255,0,0,0))
					DrawRectangle(GetMinimapX(wardstable[i].wardx)-2, GetMinimapY(wardstable[i].wardz)-2, 7, 7, wardstable[i].minicolor)
				end
			end
		end
	end
	for nID,value in pairs(rchamps) do
		local champ = objManager:GetObjectByNetworkId(nID)
		if  not champ.visible then
			if Config.rec.max then
				DrawText3D(string.format("%s: %2.1fs to recall", champ.charName, value - tim), champ.pos.x, champ.pos.y, champ.pos.z, 20, recmap, false)
			end
			if Config.rec.mini and blink then
				DrawText(champ.charName.." B",12,GetMinimapX(champ.pos.x),GetMinimapY(champ.pos.z), recmini)
			end
		end
 	end
 	if Config.wayp.enemy then
		for i, v in ipairs(enemies) do
			if not v.dead then
   				way:DrawWayPoints(v, ARGB(150, 255, 0, 0))
			end
		end
	end
	 if Config.wayp.ally then
		for i, v in ipairs(allies) do
			if not v.dead then
 				way:DrawWayPoints(v, ARGB(150, 0, 255, 0))
			end
		end
	end
end
function OnRecvPacket(p)
	if p.header == 133 then
		Trinket(p)
	end
	if p.header == 7 then
		CreateWard(p)
	end
	if p.header == 158 then
		RemoveWard(p)
	end
	if p.header == 216 then
		DecodeRecalls(p)
    end
	if p.header == 59 then
		DecodeLine(p)
	end
	if p.header == 181 then
		p.pos = 12
		local wardtype = p:Decode4()
		local type2 = nil
		if wardtype == 234594676 or wardtype == 263796882 or wardtype == 177751558 or wardtype == 101180708 then 
			type2 = 4
		elseif wardtype == 263796881 then
			type2 = 3
		elseif wardtype == 263796882 then
			type2 = 6
		elseif wardtype == 6424612 or wardtype == 6424612 then
			type2 = 5
		end
		if type2 ~= nil then
			if count == 10 then
				count = 0
			end
			local temptabled = {}
			temptabled.type = type2
			p.pos = 37
			temptabled.nID = p:DecodeF()
			p.pos = 53
			temptabled.x = p:DecodeF()
			temptabled.y = p:DecodeF()
			temptabled.z = p:DecodeF()
			temptabled.gametime = GetInGameTimer()
			wtable[count] = temptabled
			count = 1+count
		end
	end
	if p.header == 80 then
		for i=0, 50, 1 do
			if wardstable[i] ~= nil then	
				if wardstable[i].wardtype == nil and wardstable[i].suretype == false then
					DefineWardType(p, i)
					break
				end
			end
		end
	end
end
function RecallStarted(rhero, rtime)
	if rhero.visible or Config.rec.chat == false then 
		return 
	end
	PrintChat(string.format("%s: recall started (%.0fs)", rhero.charName, rtime))
end
function RecallAborted(rhero)
	if rhero.visible or Config.rec.chat == false then 
		return 
	end
	PrintChat(rhero.charName.." : recall aborted")
end
function RecallFinished(rhero)  
	if rhero.visible or Config.rec.chat == false then 
		return 
	end 
	PrintChat(rhero.charName.." : recall finished")
end
function DecodeRecalls(p)
    
        p.pos = 5
        local nID = p:DecodeF()
        p.pos = 112
        local hero = objManager:GetObjectByNetworkId(nID)
		local typ = p:Decode1()
        
        if hero then 
			if hero.team == me.team then return end
            if typ == 6  then
            	if rtimes[hero:GetSpellData(RECALL).name] ~= nil then
            		t = rtimes[hero:GetSpellData(RECALL).name]
            	else
            		t = 7
            	end
                rchamps[nID] = (GetInGameTimer() + t)
				RecallStarted(hero, t)
            elseif typ == 4 and rchamps[nID] ~= nil then
				ti = GetInGameTimer() - rchamps[nID]
				rchamps[nID] = nil
				if ti > -0.1 and ti < 0.1 then 
						RecallFinished(hero)
				else
						RecallAborted(hero)
				end
            end
            
        end
end
function CreateWard(p)
	local temptable = {}
	p.pos = 1
	temptable.wardID = p:DecodeF()
	p.pos = 5
	temptable.champID = p:DecodeF()
	temptable.champ = objManager:GetObjectByNetworkId(temptable.champID)
	if temptable.champ.team == me.team then
		if not Config.wardst.ally then
			return
		end
	else
		if not Config.wardst.enemy then
			return
		end
	end
	temptable.creationtime = GetInGameTimer()
	temptable.positionknown = false
	temptable.champx = nil
	temptable.champz = nil
	temptable.wardx = nil
	temptable.wardy = nil
	temptable.wardz = nil
	temptable.wardtype = nil
	temptable.suretype = false
	temptable.isfresh = true
	temptable.isdrawn = true
	temptable.minicolor = 0
	temptable.timeleft = 0
	for i = 0, 50, 1 do
		if wardstable[i] == nil then
			wardstable[i] = temptable
			return
		end
	end
end
function DecodeLine(p)
	p.pos = 137
	local objectID = p:DecodeF()
	for i = 0, 50, 1 do
		if wardstable[i] ~= nil then		
			if objectID < wardstable[i].wardID + 0.0000006 and objectID > wardstable[i].wardID - 0.0000006 then
				p.pos = 53
				wardstable[i].champx = p:DecodeF()
				p.pos = 61
				wardstable[i].champz = p:DecodeF()
				p.pos = 153
				wardstable[i].wardx = p:DecodeF()
				wardstable[i].wardy = wardstable[i].champ.pos.y
				p.pos = 161
				wardstable[i].wardz = p:DecodeF()
				wardstable[i].positionknown = true
				return
			end
		end
	end
end
function CheckObj()
	for k = 1, objManager.maxObjects do
		local object = objManager:GetObject(k)
		if object ~= nil and (object.name == "SightWard" or object.name == "VisionWard") then
			UpdateWard(object)
		end
	end
	local size = 0
	for i = 0, 50, 1 do
		if wardstable[i] ~= nil then
			size = 1 + size
		end
	end
	if size == 51 then
		for i = 0, 50, 1 do
			wardstable[i] = nil
		end
	end
end
function RemoveWard(p)
	p.pos=1
	local ID = p:DecodeF()
	for i=0, 50, 1 do
		if wardstable[i] ~= nil then
			if wardstable[i].wardID == ID then
				wardstable[i] = nil
			end
		end
	end
end
function SetWardType(p)
	p.pos = 1
	local wardID = p:DecodeF()
	for i=0, 50, 1 do
		if wardstable[i] ~= nil then
			if wardstable[i].suretype == false and wardstable[i].wardID == wardID then
				p.pos = 6
				local byte6 = p:Decode1()
				p.pos = 23
				local byte23 = p:Decode1()
				if byte6 == 2 and byte23 == 52 or byte23 == 51 then
					wardstable[i].wardtype = 4
					wardstable[i].suretype = true
				elseif byte6 == 1 and byte23 == 240 or byte23 == 112 then
					wardstable[i].wardtype = 3
					wardstable[i].suretype = true
				elseif byte6 == 1 and byte23 == 195 then
					wardstable[i].wardtype = 5
					wardstable[i].suretype = true
				elseif byte6 == 3 and byte23 == 195 then
					wardstable[i].wardtype = 6
					wardstable[i].suretype = true
				end
				break
			end
		end
	end
end
function DefineWardType(p, num)
	p.pos = 16
	local wardtype = p:Decode2()
	if (wardtype>315 and wardtype<325) or (wardtype>445 and wardtype<455) then
		wardstable[num].wardtype = 0
	else
		wardstable[num].wardtype = 1
	end
end
function Trinket(p, num)
	p.pos = 1
	local champNID = p:DecodeF()
	for i=0, 50, 1 do
		if wardstable[i] ~= nil then
			if wardstable[i].wardtype == 1 and wardstable[i].champID == champNID  then
				if wardstable[i].champ.level < 9 then
					wardstable[i].wardtype = 2
				else
					wardstable[i].wardtype = 6
				end
				return
			end
		end
	end
end
function Lantern(timee)
	for i=0, 50, 1 do
		if wardstable[i] ~= nil then
			if wardstable[i].wardtype == 1  and 0.5+wardstable[i].creationtime < timee then
				wardstable[i].wardtype = 7
			end
		end
	end
end
function UpdateWard(o)
	local ID = o.networkID
	for i=0, 50, 1 do
		if wardstable[i] ~= nil then
			if wardstable[i].wardID == ID then
				wardstable[i].positionknown = true
				wardstable[i].wardx = o.pos.x
				wardstable[i].wardy = o.pos.y
				wardstable[i].wardz = o.pos.z
				if o.charName == "YellowTrinket" then
					wardstable[i].wardtype = 3
					wardstable.suretype = true
				elseif o.charName == "SightWard" then
					wardstable[i].wardtype = 4
					wardstable.suretype = true
				elseif o.charName == "VisionWard" then
					wardstable[i].wardtype = 5
					wardstable.suretype = true
				elseif o.charName == "YellowTrinketUpgrade" then
					wardstable[i].wardtype = 6
					wardstable.suretype = true
				end
			end
		end
	end
end
function NotFresh(timee)
	for i=0, 50, 1 do
		if wardstable[i] ~= nil then
			if wardstable[i].isfresh and 2+wardstable[i].creationtime < timee then
				wardstable[i].isfresh = false
				if not wardstable[i].positionknown then
					print(string.format("%s has placed a ward (%s)",wardstable[i].champ.charName, wardtypes[wardstable[i].wardtype]))
				end
			end
			local wardtext = wardtypes[wardstable[i].wardtype]
			tm = wardtimes[wardstable[i].wardtype] - timee + wardstable[i].creationtime
			if tm > 0 then
				wardstable[i].timeleft = string.format("%3.0f ",tm)
				if tm > 30 then
					wardstable[i].minicolor = wardminigreen
				else
					local t2 = math.floor(tm*8.5)
					wardstable[i].minicolor = ARGB(255, 255-t2, t2, 0)
				end
			else
				wardstable[i].timeleft = "Pink, "
				wardstable[i].minicolor = wardminipink
			end

		end
	end
	if blink then
		blink = false
	else
		blink = true
	end
	for j=0, 50, 1 do
		if wardstable[j] ~= nil then
			for i=0, 20, 1 do
				if wtable[i] ~= nil and 0.5 + wtable[i].gametime < timee then
					if wtable[i].nID < wardstable[j].wardID + 0.0000006 and wtable[i].nID > wardstable[j].wardID - 0.0000006 then
						 wardstable[j].wardtype = wtable[i].type
						 wardstable[j].wardx = wtable[i].x
						 wardstable[j].wardy = wtable[i].y
						 wardstable[j].wardz = wtable[i].z
						wtable[i] = nil
						return
					end
				end
			end
		end
	end
end

function OptimizeDrawing()
	local camx = cameraPos.x
	local camy = cameraPos.y
	local camz = cameraPos.z
	for i=0, 50, 1 do
		if wardstable[i] ~= nil and wardstable[i].positionknown then
			if math.sqrt((wardstable[i].wardx - camx)^2 + ((wardstable[i].wardz-1.3*camy) - camz)^2) < 1.2*(camy-100) then
				wardstable[i].isdrawn = true
			else
				wardstable[i].isdrawn = false
			end
		end
	end
end
function DebugWardPackets(p)
	local size = p.size
	for i=0, 50, 1 do
		if wardstable[i] ~= nil then
			local wardID = wardstable[i].wardx
			local wardx = wardstable[i].wardID
			for k=0,size, 1 do
       			p.pos = k
				if p:DecodeF() == wardx then
					print("WARDMSG: HEADER: "..p.header.."    WARDPOS: "..k)
					DebugSavePacket(p)
					DebugCheckDiffs(p)
				end
			end
		end
	end
end
function DebugSavePacket(p)
	fil:write((string.format("--------PACKET; HEADER: %d\n",p.header)))
	local size = p.size
	for j=0,size, 1 do
		p.pos = j
		local dd=p:DecodeF()
		fil:write((string.format("Byte: %d    value: %f\n",j, dd)))
	end
	fil:write((string.format("--------END OF PACKET\n")))
end
function DebugCheckDiffs(p)
	if tab1[1]==nil then
		b=true
	else 
		b=false
	end
	size=p.size
	for j=0,size, 1 do
		p.pos = j
		local dd=p:Decode1()
		if b==true then
			tab1[j]=dd
		else 
			tab2[j]=dd 
		end
	end	
	if tab2[1]~=nil and tab1[1]~=nil then
		fil:write((string.format("diffstart, packets: 1 and %d ===\n", counter)))
		ig=0
		while 1 do
			if tab2[ig]==nil and tab1[ig]==nil then
					break 
				end
				if tab2[ig]~=tab1[ig] then
					if tab2[ig]==nil or tab1[ig]==nil then
						fil:write((string.format("Byte: %d nil \n" ,ig)))
					else
						fil:write((string.format("Byte: %d    First value: %d   Second Value: %d\n" ,ig, tab1[ig], tab2[ig])))
					end
				end
			ig=ig+1
		end
		fil:write("diffend===\n")
	end
	counter=counter+1
end
function DebugLoad(p)
	counter=1
	tab1={}
	tab2={}
	fil = io.open(SCRIPT_PATH.."AwarenessDebug.txt", "a")
 	fil:write("______________NEW SESSION__________________\n")
end
function DebugUnload(p)
	fil:write("______________END OF SESSION_______________\n\n\n")
 	fil:close()
end