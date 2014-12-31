<?php exit() ?>--by Jus 187.10.181.2
if myHero.charName ~= "TwistedFate" then print('Need TF modafcker.') return end

local menu = nil

function Menu()
	menu = scriptConfig("[TF Cards]", "jus")
	menu:addSubMenu("[Settings]", "settings")
	menu.settings:addParam("onoff", "Script ON/OFF", SCRIPT_PARAM_ONOFF, true)
	menu.settings:addParam("card", "Auto Pick", SCRIPT_PARAM_LIST, 1, { "RED", "BLUE", "GOLD"} )
	menu.settings:addParam("mode", "Pick Mode", SCRIPT_PARAM_LIST, 1, { "Particles", "Object", "Packets"} )
end

function OnLoad()
	Menu()
	print("Hands Up.")
end

----------------\\ Packet //--------------
-- check for myHero packets
-- check for cards header
-- pos 7 in data from header, where contain colors 
-- compare data. - enter in game cast w and only wait for 3 cards and look the colors
-- color:( 0x52 = red,  0x42 = blue, 0x47 = gold)    												
-- decode pos to get card color after cast (W)

function OnRecvPacket(p) -- more fast
	local myPacket = Packet(p)
    if menu.settings.onoff and menu.settings.mode == 3 and
    myPacket:containsFloat(myHero.networkID) and
    p.header == 0x17 then
    	p.pos = 7 -- found packet 0x17, move to data pos 7 where is the colors
    	local color = p:Decode1()
    	if menu.settings.card == 1 and color == 0x52 then
    		CastSpell(_W)
    	elseif menu.settings.card == 2 and color == 0x42 then
    		CastSpell(_W)
    	elseif menu.settings.card == 3 and color == 0x47 then
    		CastSpell(_W)
    	end
    end
end

function OnCreateObj(obj) -- more slow
	if obj ~= nil and obj.valid and menu.settings.onoff and menu.settings.mode == 2 then
		local color = obj.name
		if menu.settings.card == 1 and color:lower():find("redcard") then
			CastSpell(_W)
		elseif menu.settings.card == 2 and color:lower():find("bluecard") then
			CastSpell(_W)
		elseif menu.settings.card == 3 and color:lower():find("goldcard") then
			CastSpell(_W)
		end
	end
end

function OnApplyParticle(unit, particle) -- not the fastes and not the slowest
	if unit ~= nil and unit.isMe and menu.settings.onoff and menu.settings.mode == 1 then
		local color = particle.name
		if menu.settings.card == 1 and color:lower():find("redcard") then
			CastSpell(_W)
		elseif menu.settings.card == 2 and color:lower():find("bluecard") then
			CastSpell(_W)
		elseif menu.settings.card == 3 and color:lower():find("goldcard") then
			CastSpell(_W)
		end
	end
end